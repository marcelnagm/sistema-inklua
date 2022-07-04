<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Content;
use Carbon\Carbon;

class PositionExpiredDateVerify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $expiring_date = Carbon::now()->subDays(20)->format('Y-m-d');
        $positions = Content::where('type', 1)
                                ->whereNotNull('user_id')
                                ->where('status', 'publicada')
                                ->whereDate('published_at', '<', $expiring_date)
                                ->update(['status' => 'expirada']);

        return response()->json('updated');
    }
}
