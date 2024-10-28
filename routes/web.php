<?php

use App\Http\Controllers\LayananBpjsController;
use App\Http\Controllers\PoliklinikController;
use Illuminate\Support\Facades\Route;

//Dashboard
Route::get('/', [PoliklinikController::class, 'index']);

// Menu Layanan BPJS
Route::get('/layanan-bpjs', [LayananBpjsController::class, 'index'])->name('layanan-bpjs');
Route::post('/upload-file', [LayananBpjsController::class, 'uploadFile'])->name('layanan-bpjs.uploadFile');
Route::post('/generate-pdf',[LayananBpjsController::class, 'generatePDF']) -> name('layanan-bpjs.generatePDF');
