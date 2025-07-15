<?php

namespace App\Http\Controllers;

use App\Jobs\NotifyUserOfImportCompletion;
use App\Jobs\ProcessCsvRow;
use App\Jobs\ValidateCsvFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

class CsvImportController extends Controller {
    public function import(Request $request) {
        $request->validate([
            'csv-files' => 'required|array',
            'csv-files.*' => 'mimes:csv,txt'
        ]);
        /** @var \Illuminate\Http\UploadedFile $file */
        foreach ($request['csv-files'] as $file) {
            $path = $file->store('csv-uploads', 'public');
            $jobs = [];
            $handle = fopen(Storage::disk('public')->path($path), 'r');
            $headers = fgetcsv($handle);
            $row = fgetcsv($handle);
            while ($row !== false) {
                $jobs[] = new ProcessCsvRow(
                    count($jobs) + 1,
                    array_combine($headers, $row),
                    $path
                );
                $row = fgetcsv($handle);
            }
            fclose($handle);
            Bus::batch([
                new ValidateCsvFile($path),
                ...$jobs,
                new NotifyUserOfImportCompletion('admin@gmail.com'),
            ])->dispatch();
        }
        return redirect()->back()->with(['message' => 'File Saved Successfully and being processed in the background']);
    }
}
