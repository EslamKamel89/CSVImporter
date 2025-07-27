<?php

use App\Events\CsvRowProcessed;
use App\Http\Controllers\CsvImportController;
use App\Http\Controllers\FailedJobsController;
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
    Route::get('/failed-jobs', [FailedJobsController::class, 'index'])->name('failed-jobs.index');
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
