<?php

namespace App\Http\Controllers;

use App\Events\BatchCompleted;
use App\Jobs\NotifyUserOfImportCompletion;
use App\Jobs\ProcessCsvRow;
use App\Jobs\ValidateCsvFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class CsvImportController extends Controller {
    public function import(Request $request) {
        $request->validate([
            'csv-files' => 'required|array',
            'csv-files.*' => 'mimes:csv,txt'
        ]);
        $batchId = Session::get('current_batch_id');
        if ($batchId) {
            $batch = Bus::findBatch($batchId);
        }
        if ($batch ?? false) {
            $this->addMoreFiles($request);
            return;
        }
        $rowCount = 0;
        $jobs = $this->buildJobsFromFiles($request['csv-files'], $rowCount);
        info('rowCount: ' . $rowCount);
        $batch = Bus::batch($jobs)
            ->then(function () {
                BatchCompleted::dispatch('success');
                Session::forget('current_batch_id');
                Session::forget('total_row_count');
                info('success');
            })
            ->catch(function () {
                BatchCompleted::dispatch('failed');
                Session::forget('current_batch_id');
                Session::forget('total_row_count');
                info('Failed');
            })
            ->finally(function () {
                // Session::forget('current_batch_id');
                // Session::forget('total_row_count');
                info('✅ success is called');
            })
            ->dispatch();
        Session::put('current_batch_id', $batch->id);
        Session::put('total_row_count', $rowCount);
        return redirect()->back()->with([
            'success' => 'File Saved Successfully and being processed in the background',
            'data' => ['total' => $rowCount]
        ]);
    }
    function addMoreFiles(Request $request) {
        $batchId = Session::get('current_batch_id');
        $batch = Bus::findBatch($batchId);
        $rowCount = 0;
        $jobs = $this->buildJobsFromFiles($request['csv-files'], $rowCount);
        $batch->add($jobs);
        $oldCount =   Session::get('total_row_count');
        Session::put('total_row_count', $oldCount + $rowCount);

        return redirect()->back()->with([
            'success' => 'New File Saved Successfully and being processed in the background',
            'data' => ['total' => $oldCount + $rowCount]
        ]);
    }
    protected function buildJobsFromFiles(array $files, int &$rowCount) {
        $jobs = [];
        /** @var \Illuminate\Http\UploadedFile $file */
        foreach ($files as $file) {
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
        }
        $jobs[] =  new NotifyUserOfImportCompletion('admin@gmail.com');
        return $jobs;
    }
}
