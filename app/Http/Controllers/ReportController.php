<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Menampilkan daftar pasien.
     */
    
     public function index(Request $request)
     {
         // Inisialisasi query untuk patient dengan eager loading diagnosis
         $query = Patient::with('diagnosis');
 
         // Cek jika ada filter tanggal
         if ($request->has('start_date') && $request->has('end_date')) {
             $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
             $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
             $query->whereBetween('tgl_registrasi', [$startDate, $endDate]);
         }
 
         //TODO: Ambil data pasien sesuai query
        $patients = $query->paginate(10);  // Menampilkan 10 pasien per halaman

        //TODO: Ambil data pendaftaran pasien baru sesuai query dengan hanya 5 data terbaru
        // Ambil pendaftar hari ini
        // $patientsToday = Patient::whereBetween('tgl_registrasi', [
        //     Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()
        //     ])->latest()->take(5)->get();
        
        $patientsToday = Patient::whereNotNull('kondisi_pulang')->orderBy( 'tanggalkeluar', 'desc' )->latest()->take(5)->get();      
        Log::info('Ambil pendaftar hari ini: ' . $patientsToday->toJson());
 
         //TODO: Card Statistik
         $newPatientsThisMonth = Patient::whereBetween('tgl_registrasi', [
             Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()
         ])->count();

         Log::info("Pasien Baru Bulan ini: " . $newPatientsThisMonth);
         
         $newPatientsLastMonth = Patient::whereBetween('tgl_registrasi', [
             Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()
             ])->count();
        
        Log::info("Pasien Baru Bulan lalu: " . $newPatientsLastMonth);
             
        $newPatientsPercentageChange = $this->calculatePercentageChange($newPatientsLastMonth, $newPatientsThisMonth);
        Log::info("Perubahan Pendaftaran Pasien dari bulan lalu:: " . $newPatientsPercentageChange . "%" . "\n");
 
        //TODO: PERSENTASE PULANG
         $dischargedPatientsThisMonth = Patient::whereBetween('tanggalkeluar', [
             Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()
         ])->count();

         
         Log::info("Pasien Pulang Bulan ini: " . $dischargedPatientsThisMonth);
        $dischargedPatientsLastMonth = Patient::whereBetween('tanggalkeluar', [
             Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()
             ])->count();
             
        Log::info("Pasien Pulang Bulan lalu: " . $dischargedPatientsLastMonth);
     
        $dischargedPatientsPercentageChange = $this->calculatePercentageChange($dischargedPatientsLastMonth, $dischargedPatientsThisMonth);
        Log::info("Perubahan Pasien Pulang dari bulan lalu:: " . $dischargedPatientsPercentageChange . "%" . "\n");


        // Persentase pasien yang pulih
        //TODO: Pasien Pulih Bulan Ini
        $recoveredPatientsThisMonth = Patient::where('kondisi_pulang', 'Pulih')
            ->whereBetween('tanggalkeluar', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->count();
        Log::info("Pasien Pulih dari bulan ini:: " . $recoveredPatientsThisMonth);

        // Pasien Pulih Bulan Lalu
        $recoveredPatientsLastMonth = Patient::where('kondisi_pulang', 'Pulih')
            ->whereBetween('tanggalkeluar', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
            ->count();
        Log::info("Pasien Pulih dari bulan lalu:: " . $recoveredPatientsLastMonth);

        // Menghitung persentase perubahan pasien pulih
        $recoveredPatientsPercentageChange = $this->calculatePercentageChange($recoveredPatientsLastMonth, $recoveredPatientsThisMonth);
        Log::info("Perubahan Pasien Pulih dari bulan lalu:: " . $recoveredPatientsPercentageChange . "%" . "\n");



        //TODO: Rata-rata durasi rawat inap untuk pasien BPJS
        $bpjsPatientsThisMonth = Patient::whereBetween('tgl_registrasi', [
            Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()
        ])->whereNotNull('tgl_registrasi')
          ->whereNotNull('tanggalkeluar')
          ->get();
    
        // Menghitung total durasi rawat inap bulan ini
        $averageStayThisMonth = $bpjsPatientsThisMonth->sum(function($patient) {
            $tglRegistrasi = Carbon::parse($patient->tgl_registrasi);
            $tglKeluar = Carbon::parse($patient->tanggalkeluar);
    
            // Pastikan tanggal keluar lebih besar dari tanggal masuk
            if ($tglKeluar->greaterThanOrEqualTo($tglRegistrasi)) {
                return $tglRegistrasi->diffInDays($tglKeluar);
            }
            return 0; // Jika data tanggal tidak valid
        });

        Log::info("Rata-Rata Rawat inap dari bulan ini:: " . $averageStayThisMonth);

        // Menghitung rata-rata durasi rawat inap bulan lalu
        $bpjsPatientsLastMonth = Patient::whereBetween('tgl_registrasi', [
            Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()
        ])->whereNotNull('tgl_registrasi')
        ->whereNotNull('tanggalkeluar')
        ->get();

        $averageStayLastMonth = $bpjsPatientsLastMonth->sum(function($patient) {
            $tglRegistrasi = Carbon::parse($patient->tgl_registrasi);
            $tglKeluar = Carbon::parse($patient->tanggalkeluar);

            // Pastikan tanggal keluar lebih besar dari tanggal masuk
            if ($tglKeluar->greaterThanOrEqualTo($tglRegistrasi)) {
                return $tglRegistrasi->diffInDays($tglKeluar);
            }
            return 0; // Jika data tanggal tidak valid
        });
        Log::info("Rata-Rata Rawat inap dari bulan lalu:: " . $averageStayLastMonth);


        // Perubahan Persentase Rata-Rata Durasi Rawat Inap
        $stayChangePercentage = $averageStayLastMonth > 0 ? (($averageStayThisMonth - $averageStayLastMonth) / $averageStayLastMonth) * 100 : 0;
        Log::info("Perubahan Rata-Rata Rawat Inap dari bulan lalu:: " . round($stayChangePercentage, 2). "%" . "\n");


        //TODO: Menampilkann Data Statistik Demografi
        // Query untuk semua data (tidak dipengaruhi paginasi)
        $allPatients = Patient::all();

        $totalPatients = $allPatients->count();
        $ageGroupsWithPercentage = [];
            // Kelompokkan pasien berdasarkan usia
        $ageGroups = [
            '0-18' => 0,
            '19-35' => 0,
            '36-50' => 0,
            '51-65' => 0,
            '65+' => 0,
        ];

        foreach ($allPatients as $patient) {
            $age = Carbon::parse($patient->tgl_lahir)->age;
            if ($age <= 18) {
                $ageGroups['0-18']++;
            } elseif ($age <= 35) {
                $ageGroups['19-35']++;
            } elseif ($age <= 50) {
                $ageGroups['36-50']++;
            } elseif ($age <= 65) {
                $ageGroups['51-65']++;
            } else {
                $ageGroups['65+']++;
            }
        }

        foreach ($ageGroups as $range => $count) {
            $percentage = $totalPatients > 0 ? round(($count / $totalPatients) * 100, 2) : 0;
            Log::info('Total Umur yang di dapatkan: ' . $percentage . '%');
            $ageGroupsWithPercentage[$range] = [
                'count' => $count,
                'percentage' => $percentage
            ];
        }

        // Distribusi registrasi pasien selama 1 tahun terakhir
        $monthlyRegistrationsCurrentYear  = Patient::selectRaw('MONTH(tgl_registrasi) as month, COUNT(*) as count')
            ->whereYear('tgl_registrasi', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Distribusi registrasi pasien tahun lalu
        $monthlyRegistrationsLastYear = Patient::selectRaw('MONTH(tgl_registrasi) as month, COUNT(*) as count')
            ->whereYear('tgl_registrasi', Carbon::now()->subYear()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

            $bulanNama = [
                1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
                9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
            ];

            // Mengisi data bulan yang kosong dan mengubah bulan ke nama bulan
            $registrationsByMonthCurrentYear = array_fill(1, 12, 0);
            foreach ($monthlyRegistrationsCurrentYear as $month => $count) {
                $registrationsByMonthCurrentYear[$month] = $count;
            }

            $registrationsByMonthLastYear = array_fill(1, 12, 0);
            foreach ($monthlyRegistrationsLastYear as $month => $count) {
                $registrationsByMonthLastYear[$month] = $count;
            }

            // Mengubah angka bulan menjadi nama bulan
            $monthLabels = [];
            foreach (range(1, 12) as $month) {
                $monthLabels[] = $bulanNama[$month];
            }
 
         // Kembalikan view dengan data pasien dan insight
         return view('reports.index', compact(
             'patients',
             'patientsToday',
             'newPatientsThisMonth', 
             'newPatientsLastMonth', 
             'newPatientsPercentageChange',
             'dischargedPatientsThisMonth',
             'dischargedPatientsLastMonth',
             'dischargedPatientsPercentageChange',
             'recoveredPatientsThisMonth',
             'recoveredPatientsLastMonth',
             'recoveredPatientsPercentageChange',
             'averageStayThisMonth',
             'stayChangePercentage',
             'registrationsByMonthCurrentYear',
             'registrationsByMonthLastYear',
             'monthLabels',
             'ageGroups',
             'ageGroupsWithPercentage',
         ));
     }

         // Helper function untuk menghitung persentase perubahan
         private function calculatePercentageChange($oldValue, $newValue)
         {
             if ($oldValue == 0 && $newValue == 0) {
                 return 0; // Tidak ada perubahan jika kedua bulan 0
             }
         
             if ($oldValue == 0) {
                 return $newValue > 0 ? 100 : -100; // Jika bulan lalu 0 dan bulan ini lebih besar dari 0, hasilkan 100%
             }
         
             $percentageChange = (($newValue - $oldValue) / $oldValue) * 100;
         
             // Tambahkan pembulatan untuk menghindari masalah presisi
             return round($percentageChange, 2);
         }
         
    

    // public function index(Request $request)
    // {
    //     $query = Patient::with('diagnosis');
    
    //     // Cek jika ada filter tanggal
    //     if ($request->has('start_date') && $request->has('end_date')) {
    //         $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
    //         $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
    //         $query->whereBetween('tgl_registrasi', [$startDate, $endDate]);
    //     }
    
    //     $patients = $query->get();
    
    //     return view('reports.index', compact('patients'));
    // }
    

    public function generateReport($id)
    {
        try {
            // Ambil data pasien berdasarkan ID
            $patient = Patient::with('diagnosis')->findOrFail($id);

            // Path ke file Jasper
            $jasperFile = public_path('storage/jasper/Resume.jasper');
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
                'ID_Pasien' => $patient -> ID_Pasien,
                'ruang' => $patient -> ruang,
                'tanggalkeluar' => $patient ->tanggalkeluar,
            ];
            
            $jasperExecutable = '"C:\\Program Files (x86)\\JasperStarter\\bin\\jasperstarter.exe"';
            $dbHost = env('DB_HOST', 'localhost');
            $dbPort = env('DB_PORT', '3306');
            $dbUser = env('DB_USERNAME', 'root');
            $dbPass = env('DB_PASSWORD', '');
            $dbName = env('DB_DATABASE', 'dashboard_rs_paru');

            $command = [
                $jasperExecutable,
                'pr',
                escapeshellarg($jasperFile),
                '-t mysql',
                '-H', $dbHost,
                '-n', $dbName,
                '-u', $dbUser,
                '-o',
                escapeshellarg($outputPath),
                '-f',
                'pdf',
                '-P',
            ];
            foreach ($parameters as $key => $value) {
                // $command[] = escapeshellarg("{$key}={$value}");
                $command[] = escapeshellarg($key . '="' . $value . '"');
                Log::info("Parameter {$key} = " . (is_null($value) ? 'NULL' : $value));
            }

            // $command[] = '--db-url jdbc:mysql://192.168.5.100/db-tester';
    
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
}
