<?php

use App\Events\CsvRowProcessed;
use App\Http\Controllers\CsvImportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('home');
    Route::get('/csv-upload', function () {
        return Inertia::render('csv/UploadCsv');
    })->name('csv.upload');
    Route::post('/csv-import', [CsvImportController::class, 'import'])->name('csv.import');
    // Route::get('/add-more-files', [CsvImportController::class, 'addMoreFiles']);
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
