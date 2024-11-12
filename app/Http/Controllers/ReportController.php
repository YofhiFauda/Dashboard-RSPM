<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Menampilkan daftar pasien.
     */
    public function index()
    {
        $patients = Patient::with('diagnosis')->get();  // Include diagnosis
        return view('reports.index', compact('patients'));
    }

    public function generateReport($id)
    {
        try {
            // Ambil data pasien berdasarkan ID
            $patient = Patient::with('diagnosis')->findOrFail($id);

            // Path ke file Jasper
            $jasperFile = public_path('storage/jasper/rptLaporanResume.jasper');
            Log::info("Path ke file Jasper: " . $jasperFile);

            // Cek apakah file Jasper ada
            if (!file_exists($jasperFile)) {
                throw new \Exception("File Jasper tidak ditemukan di path: " . $jasperFile);
            }

            // Tentukan output path
            $outputPath = public_path('storage/pdf/') . 'ResumeMedisPasien_' . $patient->ID_Pasien . '_' . time();
            
            Log::error("output path: " . $outputPath);

            $parameters = [
                'namars' => 'RUMAH SAKIT PARU MADIUN',
                'alamatrs' => 'jl. yosudarso no 8',
                'kotars' => 'Kota Madiun',
                'propinsirs' => 'Jawa Timur',
                'kontakrs' => '081235513679',
                'emailrs' => 'rspm@gmail.com',
                'ruang' => $patient -> ruang,
                'tanggalkeluar' => $patient ->tanggalkeluar,
            ];
            
    
            $jasperExecutable = '"C:\\Program Files (x86)\\JasperStarter\\bin\\jasperstarter.exe"';
    
            $command = [
                $jasperExecutable,
                'pr',
                escapeshellarg($jasperFile),
                '-o',
                escapeshellarg($outputPath),
                '-f',
                'pdf',
                '-P',
            ];
    
            foreach ($parameters as $key => $value) {
                $command[] = escapeshellarg("{$key}={$value}");
                Log::info("Parameter {$key} = " . (is_null($value) ? 'NULL' : $value));
            }

            $command[] = '--db-url jdbc:mysql://127.0.0.1:3306/dashboard_rs_paru';

            

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

            // Cek apakah proses berjalan dengan sukses
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            // Path file PDF yang dihasilkan
            $pdfPath = $outputPath . '.pdf';
            Log::info("Path file PDF yang dihasilkan: " . $pdfPath);

            // Cek apakah file PDF ada
            if (!file_exists($pdfPath)) {
                throw new \Exception("Laporan tidak berhasil dibuat.");
            }

            // Kirim file PDF sebagai download dan hapus file setelah dikirim
            return response()->download($pdfPath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error("Gagal menghasilkan laporan: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghasilkan laporan. Silakan coba lagi.');
        }
    }

    // public function generateReport($id)
    // {
    //     try {
    //         // Ambil data pasien berdasarkan ID
    //         $patient = Patient::findOrFail($id);

    //         // Path ke file Jasper
    //         $jasperFile = public_path('storage/jasper/rptLaporanResume.jasper');
    //         Log::info("Path ke file Jasper: " . $jasperFile);

    //         // Cek apakah file Jasper ada
    //         if (!file_exists($jasperFile)) {
    //             throw new \Exception("File Jasper tidak ditemukan di path: " . $jasperFile);
    //         }

    //         // Tentukan output path
    //         $outputPath = public_path('storage/pdf/') . 'ResumeMedisPasien_' . $patient->ID_Pasien . '_' . time();
    //         Log::error("output path: " . $outputPath);
    //         // Parameter untuk laporan
    //         // Parameter untuk laporan, gunakan Nama_Pasien
    //         $parameters = [
    //             'namars' => 'RUMAH SAKIT PARU MADIUN',  // Hardcode nama rumah sakit
    //             'alamatrs' => 'jl. yosudarso no 8',  // Alamat rumah sakit
    //             'kotars' => 'Kota Madiun',  // Kota rumah sakit
    //             'propinsirs' => 'Jawa Timur',  // Provinsi rumah sakit
    //             'kontakrs' => '081235513679',  // Kontak rumah sakit
    //             'emailrs' => 'rspm@gmail.com',  // Email rumah sakit
    //         ];


    //         // Jalankan JasperStarter untuk menghasilkan PDF
    //         // Contoh perintah:
    //         // jasperstarter run /path/to/report.jasper -o /path/to/output -f pdf -P "ID_Pasien=1"



    //         #jasperstarter pr "C:/xampp/htdocs/dashboard-rs-paru/public/storage/jasper/rptLaporanResume.jasper" -o "C:/xampp/htdocs/dashboard-rs-paru/public/storage/pdf/ResumeMedisPasien_1_1731143607.pdf" -f pdf -P namars="RUMAH SAKIT PARU MADIUN" -P alamatrs="jl. yosudarso no 8" -P kotars="Kota Madiun" -P propinsirs="Jawa Timur" -P kontakrs="081235513679" -P emailrs="rspm@gmail.com"
            
    //         $command = [
    //             // 'jasperstarter',
    //             'C:\\Program Files (x86)\\JasperStarter\\bin\\jasperstarter.exe',
    //             'pr',
    //             $jasperFile,
    //             '-o',
    //             $outputPath,
    //             '-f',
    //             'pdf',
    //             '-P',
    //             $parameters
    //         ];

            

    //         $process = Process::fromShellCommandline(implode(" ", $command));

    //         $process->setTimeout(3600); // 1 jam timeout
    //         $process->run();

    //         // Tambahkan setelah proses
    //         $output = $process->getOutput();
    //         $error = $process->getErrorOutput();
    //         Log::info("JasperStarter Output: " . $output);
    //         Log::error("JasperStarter Error: " . $error);

    //         // Cek apakah proses berjalan dengan sukses
    //         if (!$process->isSuccessful()) {
    //             throw new ProcessFailedException($process);
    //         }

    //         // Path file PDF yang dihasilkan
    //         $pdfPath = $outputPath . '.pdf';
    //         Log::error("Path file PDF yang dihasilkan: " . $pdfPath);

    //         // Cek apakah file PDF ada
    //         if (!file_exists($pdfPath)) {
    //             throw new \Exception("Laporan tidak berhasil dibuat.");
    //         }

    //         // Kirim file PDF sebagai download dan hapus file setelah dikirim
    //         return response()->download($pdfPath)->deleteFileAfterSend(true);

    //     } catch (\Exception $e) {
    //         Log::error("Gagal menghasilkan laporan: " . $e->getMessage());
    //         return redirect()->back()->with('error', 'Gagal menghasilkan laporan. Silakan coba lagi.');
    //     }
    // }
}
