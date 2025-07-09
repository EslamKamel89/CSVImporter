<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyUserOfImportCompletion implements ShouldQueue {
    use Queueable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $email) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        info("🔔 Notifying user at {$this->email} that import is complete.");
    }
}
