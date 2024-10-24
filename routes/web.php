<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PoliklinikController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PoliklinikController::class, 'index']);
