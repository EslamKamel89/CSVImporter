<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FailedJobsController extends Controller {
    public function index() {
        $failedJobs = \DB::table('failed_jobs')
            ->select(['id', 'connection', 'queue', 'payload', 'exception', 'failed_at'])
            ->orderBy('failed_at', 'desc')
            ->get();
        // dd($failedJobs);
        $jobs =  $failedJobs->map(function ($job) {
            $payload = json_decode($job->payload, true);
            // dd($payload);
            return [
                'id' => $job->id,
                'queue' => $job->queue,
                'exception' => $job->exception,
                'failed_at' => $job->failed_at,
                'job_name' => $payload['displayName'] ?? 'Unkwon Job',
                'data' => $this->extractJobData($payload),
            ];
        });
        return inertia('FailedJobs/Index', ['jobs' => $jobs]);
    }
    protected function extractJobData(array $payload) {
        $command = $payload['data']['command'] ?? null;
        if (! $command) {
            return 'No command data';
        }
        try {
            $job = unserialize($command);
            // dd($job);
            if ($job instanceof \App\Jobs\ProcessCsvRow) {
                // dd("Row #{$job->rowNumber} in {$job->fileName}");
                return "Row #{$job->rowNumber} in {$job->fileName}";
            }
            if ($job instanceof \App\Jobs\ValidateCsvFile) {
                // dd("Validation failed for {$job->filePath}");
                return "Validation failed for {$job->filePath}";
            }
            return get_class($job);
        } catch (\Exception $e) {
            return 'Failed to unserialize job data: ' . $e->getMessage();
        }
    }
}
