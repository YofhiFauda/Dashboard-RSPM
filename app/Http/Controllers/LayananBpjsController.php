<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;


class LayananBpjsController extends Controller
{
    public function index()
    {
        return view('LayananBpjs.layananBpjs');
    }

    public function generatePdf(Request $request)
    {
        // Ambil jalur file jasper dari session
        $jasperFile = session('jasper_file');
    
        // Cek apakah file jasper ada
        if (!$jasperFile || !file_exists($jasperFile)) {
            return redirect()->back()->withErrors('Jasper file not found. Please upload a file first.');
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
    
        try {
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

    public function uploadFile(Request $request)
    {
        // Validasi file
    // Validasi file
        $request->validate([
            'file' => 'required|file|max:4098',
        ]);
        if ($request->hasFile('file')) {
            // Dapatkan informasi file
            $file = $request->file('file');

            // Dapatkan nama asli file
            $originalFileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            // Log informasi file
            Log::info('Uploaded file name: ' . $originalFileName);
            Log::info('Uploaded file path: ' . $file->getRealPath());
            Log::info('Uploaded file size: ' . $file->getSize());
            Log::info('Uploaded file MIME type: ' . $file->getMimeType());

            // Cek ekstensi file
            if (!in_array($extension, ['jasper', 'pdf'])) {
                return back()->withErrors(['file' => 'File must be a .jasper or .pdf file.']);
            }

            // Membuat nama folder baru berdasarkan nama file tanpa ekstensi
            // $folderName = pathinfo($originalFileName, PATHINFO_FILENAME);
            
            // Menentukan path penyimpanan
            $storagePath = public_path('storage/jasper/');
            Log::info('penyimpanan file : ' . $storagePath);

            // Buat direktori jika belum ada
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true);
            }

            // Menyimpan file ke lokasi yang ditentukan
            $file->move($storagePath, $originalFileName);
            Log::info('Menyimpan file ke lokasi yang ditentukan : ' . $file);

            // Simpan jalur file jasper di session
            session(['jasper_file' => $storagePath . $originalFileName]);

            // Mengembalikan respon setelah file berhasil diupload
            return back()->with('success', 'File uploaded successfully: ' . $originalFileName);
        }

        return back()->withErrors(['file' => 'No file was uploaded.']);
    }
}