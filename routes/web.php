<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('home');
    Route::get('/csv-upload', function () {
        return Inertia::render('csv/UploadCsv');
    })->name('csv.upload');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
