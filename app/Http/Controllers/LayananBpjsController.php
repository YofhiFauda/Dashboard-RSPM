<?php

namespace App\Http\Controllers;

use App\Models\BpjsService;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPJasper\PHPJasper;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LayananBpjsController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::connection('production')->table('resume_pasien');
    
        // Jika ada pencarian, tambahkan kondisi pencarian
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('resume_pasien.keluhan_utama', 'like', "%{$search}%")
                  ->orWhere('resume_pasien.diagnosa_utama', 'like', "%{$search}%")
                  ->orWhere('resume_pasien.kd_diagnosa_utama', 'like', "%{$search}%")
                  ->orWhere('resume_pasien.diagnosa_sekunder', 'like', "%{$search}%")
                  ->orWhere('resume_pasien.kd_diagnosa_sekunder', 'like', "%{$search}%")
                  ->orWhere('resume_pasien.diagnosa_sekunder2', 'like', "%{$search}%")
                  ->orWhere('resume_pasien.kd_diagnosa_sekunder2', 'like', "%{$search}%")
                  ->orWhere('resume_pasien.diagnosa_sekunder3', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan tgl_registrasi di tabel reg_periksa
        if ($request->has('start_date') && !empty($request->input('start_date'))) {
            $startDateReg = $request->input('start_date');
            $query->whereExists(function($subQuery) use ($startDateReg) {
                $subQuery->select(DB::raw(1))
                        ->from('reg_periksa')
                        ->whereColumn('reg_periksa.no_rawat', 'resume_pasien.no_rawat')
                        ->whereDate('reg_periksa.tgl_registrasi', '>=', $startDateReg);
            });
        }

        if ($request->has('end_date') && !empty($request->input('end_date'))) {
            $endDateReg = $request->input('end_date');
            $query->whereExists(function($subQuery) use ($endDateReg) {
                $subQuery->select(DB::raw(1))
                        ->from('reg_periksa')
                        ->whereColumn('reg_periksa.no_rawat', 'resume_pasien.no_rawat')
                        ->whereDate('reg_periksa.tgl_registrasi', '<=', $endDateReg);
            });
        }
    
        // Ambil jumlah item per halaman dari request, default ke 10 jika tidak ada
        $itemsPerPage = $request->input('itemsPerPage', 10); // Pastikan ini sesuai dengan nama input di frontend
    
        // Lakukan paginasi pada query
        $result = $query->paginate($itemsPerPage);
    
        // Kembalikan hasil ke view
        return view('bpjs.resumePasien', compact('result'));
    }

    public function generateReport(Request $request)
    {
        // Cek apakah route benar dipanggil dan $id diterima
        try {
            $noRawat = $request->query('no_rawat');
                // Dekode URL untuk memastikan ID dalam format yang benar
            Log::info('Generating report for ID: ' . $noRawat);
            // Pastikan ID valid dan ada di database
            // Query untuk mendapatkan data pasien berdasarkan ID

            $patient = DB::connection('production')->table('resume_pasien')
            ->where('no_rawat', $noRawat)
            ->first(); // Menggunakan `first()` untuk mengambil data pasien berdasarkan no_rawat

            // Log data pasien untuk debug
            Log::info('Patient data:', (array)$patient);
    
            // Cek jika data pasien ditemukan
            if (!$patient) {
                return response()->json(['error' => 'Data tidak ditemukan'], 404);
            }
    
            // Path ke file Jasper
            $jasperFile = public_path('storage/jasper/rptLaporanResume.jasper');
            Log::info("Path ke file Jasper: " . $jasperFile);
            if (!file_exists($jasperFile)) {
                throw new \Exception("File Jasper tidak ditemukan di path: " . $jasperFile);
            }
    
            // Tentukan output path
            $outputPath = public_path('storage/pdf/') . 'ResumeMedisPasien_' . '_' . time();
            Log::error("output path: " . $outputPath);

            // Parameter Jasper
            $parameters = [
                'namars' => 'RUMAH SAKIT PARU MADIUN',
                'alamatrs' => 'Jl. Yos Sudarso No.108-112',
                'kotars' => 'Kota Madiun',
                'propinsirs' => 'Jawa Timur',
                'kontakrs' => '+62 851-7692-1876',
                'emailrs' => 'rspmanguharjo@gmail.com',
                'norawat'   => $patient ->no_rawat ?? '',
                'ruang' => $patient -> ruang  ?? '',
                'tanggalkeluar' => $patient ->tanggalkeluar  ?? '',
            ];
    
            // Perintah eksekusi JasperStarter
            $jasperExecutable = '"C:\\Program Files (x86)\\JasperStarter\\bin\\jasperstarter.exe"';
            $dbHost = env('PRODUCTION_DB_HOST', '192.168.5.100');
            $dbPort = env('PRODUCTION_DB_PORT', '3306');
            $dbUser = env('PRODUCTION_DB_USERNAME', 'db-tester');
            $dbPass = env('PRODUCTION_DB_PASSWORD', 'mYbstZ4Xe4tnAfrx');
            $dbName = env('PRODUCTION_DB_DATABASE', 'db-tester');
            $command = [
                $jasperExecutable,
                'pr',
                escapeshellarg($jasperFile),
                '-t mysql',
                '-H', $dbHost,
                '-n', $dbName,
                '-u', $dbUser,
                '-p', $dbPass,
                '-o',
                escapeshellarg($outputPath),
                '-f',
                'pdf',
                '-P',
            ];

            foreach ($parameters as $key => $value) {
                // $command[] = escapeshellarg("{$key}={$value}");
                $command[] = "{$key}=" . escapeshellarg($value);
                Log::info("Parameter {$key} = " . (is_null($value) ? 'NULL' : $value));
            }

    
            // Jalankan perintah JasperStarter
            $process = Process::fromShellCommandline(implode(" ", $command));
            Log::info("JasperStarter Command: " . implode(" ", $command));
            $process->setTimeout(3600); // 1 jam timeout
            $process->run(function ($type, $buffer) {
                if (Process::ERR === $type) {
                    Log::error($buffer);
                } else {
                    Log::info($buffer);
                }
            });

            // Tambahkan setelah proses
            $output = $process->getOutput();
            $error = $process->getErrorOutput();
            Log::info("JasperStarter Output: " . $output);
            Log::error("JasperStarter Error: " . $error);
    
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
    
            $pdfPath = $outputPath . '.pdf';
            Log::info("Path file PDF yang dihasilkan: " . $pdfPath);

            if (!file_exists($pdfPath)) {
                throw new \Exception("Laporan tidak berhasil dibuat.");
            }
    
            // Download file PDF
            return response()->download($pdfPath)->deleteFileAfterSend(true);
    
        } catch (\Exception $e) {
            Log::error("Gagal menghasilkan laporan untuk ID {$noRawat}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghasilkan laporan. Silakan coba lagi.');
        }
    }
}