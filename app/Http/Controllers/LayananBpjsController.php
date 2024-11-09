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
    public function index()
    {
        $data = BpjsService::all();
        return view('LayananBpjs.layananBpjs', compact('data'));
    }

    public function generatePdf(Request $request)
    {
        // Mengambil ID dari query string
        $id = $request->id;

        Log::info('ID received for PDF generation: ' . $id); // Check the ID received

        // Fetch data from the database using the provided ID
        $data = BpjsService::find($id);
        Log::info("Data Fetch: " . json_encode($data));

        if (!$data) {
            return redirect()->back()->withErrors('Data not found.');
        }

        // Get the Jasper file path from the database
        $jasperFile = $data->file_jasper; // Menggunakan jalur absolut
        Log::info("Letak Dari Jasper file: " . $jasperFile);

        
        // Check if Jasper file exists
        if (!$jasperFile || !file_exists($jasperFile)) {
            return redirect()->back()->withErrors('Jasper file not found.');
        }
    
        // Dapatkan nama asli file jasper tanpa ekstensi
        $originalFileName = pathinfo($jasperFile, PATHINFO_FILENAME);
        $outputPdfPath = public_path("storage/pdf/{$originalFileName}.pdf");
    
        // Definisikan perintah Java
        $javaExecutable = '"C:\\Program Files\\Java\\jdk-21\\bin\\java.exe"';
        $jasperLibPath = public_path('storage/jasper_lib');
        $javaSrcPath = base_path('app/generatepdf/src/main/java');
        $argFilePath = 'C:\\Users\\yopip\\AppData\\Local\\Temp\\cp_5p9mscu70le1jsnjipns8lzry.argfile';
    
        $javaClass = 'com.generatepdf.CompileReport';
    
        // Perintah baru tanpa `cmd /c`
        $command = "cd /d $javaSrcPath && $javaExecutable @$argFilePath $javaClass \"$jasperFile\" \"$outputPdfPath\"";

        // Inisialisasi proses dengan `fromShellCommandline`
        $process = Process::fromShellCommandline($command);

        // Set timeout 120 seconds
        $process->setTimeout(120);
        $process->run(function ($type, $buffer) {
            if (Process::ERR === $type) {
                Log::error($buffer);
            } else {
                Log::info($buffer);
            }
        });
    
        try {
            Log::info("Starting PDF generation...");
            Log::info("Command executed: " . $command);
            Log::info("Generating PDF from Jasper file: " . $jasperFile);
            Log::info("Output PDF path: " . $outputPdfPath);
    
            $process->mustRun();
    
            Log::info("PDF successfully generated: " . $outputPdfPath);
    
            // Periksa apakah file PDF ada setelah proses
            if (!file_exists($outputPdfPath)) {
                return redirect()->back()->withErrors('PDF generation failed. Output file not found.');
            }
    
            return response()->download($outputPdfPath, "{$originalFileName}.pdf")->deleteFileAfterSend(true);
        } catch (ProcessFailedException $exception) {
            Log::error("Failed to generate PDF: " . $exception->getMessage());
            Log::error("Process output: " . $process->getErrorOutput());
    
            return redirect()->back()->withErrors('Failed to generate PDF.');
        }
    }

    public function resumePasien(Request $request)
    {
        $query = DB::connection('production')->table('resume_pasien');
    
        // Jika ada pencarian, tambahkan kondisi pencarian
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('keluhan_utama', 'like', "%{$search}%")
                  ->orWhere('diagnosa_utama', 'like', "%{$search}%")
                  ->orWhere('kd_diagnosa_utama', 'like', "%{$search}%")
                  ->orWhere('diagnosa_sekunder', 'like', "%{$search}%")
                  ->orWhere('kd_diagnosa_sekunder', 'like', "%{$search}%")
                  ->orWhere('diagnosa_sekunder2', 'like', "%{$search}%")
                  ->orWhere('kd_diagnosa_sekunder2', 'like', "%{$search}%")
                  ->orWhere('diagnosa_sekunder3', 'like', "%{$search}%");
            });
        }
    
        // Ambil jumlah item per halaman dari request, default ke 10 jika tidak ada
        $itemsPerPage = $request->input('itemsPerPage', 10); // Pastikan ini sesuai dengan nama input di frontend
    
        // Lakukan paginasi pada query
        $result = $query->paginate($itemsPerPage);
    
        // Kembalikan hasil ke view
        return view('LayananBpjs.resumePasien', compact('result'));
    }

    // public function uploadFile(Request $request)
    // {
    //     // Validasi file
    // // Validasi file
    //     $request->validate([
    //         'file' => 'required|file|max:4098',
    //     ]);
    //     if ($request->hasFile('file')) {
    //         // Dapatkan informasi file
    //         $file = $request->file('file');

    //         // Dapatkan nama asli file
    //         $originalFileName = $file->getClientOriginalName();
    //         $extension = $file->getClientOriginalExtension();

    //         // Log informasi file
    //         Log::info('Uploaded file name: ' . $originalFileName);
    //         Log::info('Uploaded file path: ' . $file->getRealPath());
    //         Log::info('Uploaded file size: ' . $file->getSize());
    //         Log::info('Uploaded file MIME type: ' . $file->getMimeType());

    //         // Cek ekstensi file
    //         if (!in_array($extension, ['jasper', 'pdf'])) {
    //             return back()->withErrors(['file' => 'File must be a .jasper or .pdf file.']);
    //         }

    //         // Membuat nama folder baru berdasarkan nama file tanpa ekstensi
    //         // $folderName = pathinfo($originalFileName, PATHINFO_FILENAME);
            
    //         // Menentukan path penyimpanan
    //         $storagePath = public_path('storage/jasper/');
    //         Log::info('penyimpanan file : ' . $storagePath);

    //         // Buat direktori jika belum ada
    //         if (!file_exists($storagePath)) {
    //             mkdir($storagePath, 0777, true);
    //         }

    //         // Menyimpan file ke lokasi yang ditentukan
    //         $file->move($storagePath, $originalFileName);
    //         Log::info('Menyimpan file ke lokasi yang ditentukan : ' . $file);

    //         // Simpan jalur file jasper di session
    //         session(['jasper_file' => $storagePath . $originalFileName]);

    //         // Mengembalikan respon setelah file berhasil diupload
    //         return back()->with('success', 'File uploaded successfully: ' . $originalFileName);
    //     }

    //     return back()->withErrors(['file' => 'No file was uploaded.']);
    // }
}