<?php

use App\Http\Controllers\LayananBpjsController;
use App\Http\Controllers\PoliklinikController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

//Dashboard
Route::get('/', [PoliklinikController::class, 'index']);

// Menu Layanan BPJS
Route::get('/layanan-bpjs', [LayananBpjsController::class, 'index'])->name('layanan-bpjs');
Route::get('/resume-pasien', [LayananBpjsController::class, 'resumePasien'])->name('layanan-bpjs.resumePasien');
Route::get('/generate-report/{id}', [LayananBpjsController::class, 'generateReport'])->name('layanan-bpjs.generateReport');

Route::post('/upload-file', [LayananBpjsController::class, 'uploadFile'])->name('layanan-bpjs.uploadFile');
Route::post('/generate-pdf',[LayananBpjsController::class, 'generatePDF']) -> name('layanan-bpjs.generatePDF');


// Menampilkan daftar pasien
Route::get('/patients', [ReportController::class, 'index'])->name('patients.index');
// Menghasilkan laporan untuk pasien tertentu
Route::get('/patients/{ID_patient}/generate-report', [ReportController::class, 'generateReport'])->name('patients.generateReport');
