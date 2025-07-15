<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ValidateCsvFile implements ShouldQueue {
    use Queueable, SerializesModels, InteractsWithQueue, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $filePath) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        info("✅ Validating file: {$this->filePath}");
        if (!Storage::disk('public')->exists($this->filePath)) {
            throw new \Exception("File not found at path: {$this->filePath}");
        }
        $file = fopen(Storage::disk('public')->path($this->filePath), 'r');
        $headers = fgetcsv($file);
        if (!$headers || !in_array('email', $headers)) {
            fclose($file);
            throw new \Exception('Missing required column email');
        }
        fclose($file);
    }
}
