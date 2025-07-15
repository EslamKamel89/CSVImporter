<?php

namespace App\Jobs;

use App\Events\CsvRowProcessed;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;
use Storage;

class ProcessCsvRow implements ShouldQueue {
    use Queueable, SerializesModels, InteractsWithQueue, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $rowNumber, public array $data, public string $fileName) {
        // info('ProcessCsvRow', [$this->rowNumber, $this->data, $this->fileName]);
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        info("📦 Processing row #{$this->rowNumber} from {$this->fileName}", $this->data);
        sleep(1);
        Event::dispatch(
            new CsvRowProcessed($this->fileName, $this->rowNumber, $this->data)
        );
    }
    public function failed(\Throwable $exception) {
        info("💥 Failed to process row #{$this->rowNumber}: " . $exception->getMessage());
    }
}
