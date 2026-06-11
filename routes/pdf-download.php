<?php

use App\Http\Controllers\Frontend\PdfController;
use Illuminate\Support\Facades\Route;

Route::get('/download-policies-pdf', [PdfController::class, 'downloadPoliciesPdf'])->name('download-policies-pdf');
