<?php

namespace App\Jobs;

use App\Http\Services\PenggajianService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PenggajianJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $bulan,
        public int|string $tahun)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $opr = PenggajianService::summarize($this->bulan, $this->tahun);
            Log::debug($opr);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }
}
