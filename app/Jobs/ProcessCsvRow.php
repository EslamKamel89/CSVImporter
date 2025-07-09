<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Storage;

class ProcessCsvRow implements ShouldQueue {
    use Queueable, SerializesModels, InteractsWithQueue;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $rowNumber, public array $data, public string $fileName) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        info("📦 Processing row #{$this->rowNumber} from {$this->fileName}", $this->data);
        sleep(2);
    }
    public function failed(\Throwable $exception) {
        info("💥 Failed to process row #{$this->rowNumber}: " . $exception->getMessage());
    }
}
