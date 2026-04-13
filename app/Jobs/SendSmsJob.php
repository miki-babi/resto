<?php

namespace App\Jobs;

use App\Services\SmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendSmsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $phone,
        public string $message,
    ) {}

    /**
     * Execute the job.
     */
   public $tries = 3;

public function handle()
{
    try {
        SmsService::send($this->phone, $this->message);
        Log::info("SMS sent to {$this->phone}");
    } catch (\Exception $e) {
        Log::error("SMS failed: " . $e->getMessage());
        throw $e; // so retry works
    }
}
}
