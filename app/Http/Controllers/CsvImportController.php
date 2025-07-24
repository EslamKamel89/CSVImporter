<?php

namespace App\Http\Controllers;

use App\Events\BatchCompleted;
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
        $jobs = [];
        $rowCount = 0;
        /** @var \Illuminate\Http\UploadedFile $file */
        foreach ($request['csv-files'] as $file) {
            $path = $file->store('csv-uploads', 'public');
            $handle = fopen(Storage::disk('public')->path($path), 'r');
            $headers = fgetcsv($handle);
            $row = fgetcsv($handle);
            $jobs[] = new ValidateCsvFile($path);

            while ($row !== false) {
                $jobs[] = new ProcessCsvRow(
                    ++$rowCount,
                    array_combine($headers, $row),
                    $path
                );
                $row = fgetcsv($handle);
            }
            fclose($handle);
            $jobs[] =  new NotifyUserOfImportCompletion('admin@gmail.com');
        }
        $batch = Bus::batch($jobs)
            ->then(function () {
                BatchCompleted::dispatch('success');
                info('success');
            })
            ->catch(function () {
                BatchCompleted::dispatch('failed');
                info('Failed');
            })
            ->dispatch();
        return redirect()->back()->with([
            'success' => 'File Saved Successfully and being processed in the background',
            'data' => ['total' => $rowCount]
        ]);
    }
}
