<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;
use Carbon\Carbon;

class BoletoVerify implements ShouldQueue
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
        $due_date = Carbon::now()->subDays(4)->format('Y-m-d');
        $transactions = Transaction::where('payment_method', 'boleto')->where('status', 'pending')->whereDate('due_date', '>=', $due_date)->get();
        foreach($transactions as $transaction) {
            $pagarme = json_decode($transaction->getOrder());
            if($pagarme->status == 'paid') {
                $transaction->content->update(['status' => 'publicada', 'published_at' => Carbon::now()->format('Y-m-d')]);
                $transaction->content->notifyPositionPublished();
                $transaction->update(['status' => 'paid', 'charge_status' => 'paid']);
                return response()->json('updated');
            }
        }

        return response()->json('not updated');
    }
}
