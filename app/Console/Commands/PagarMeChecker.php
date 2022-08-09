<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Content;
use App\Jobs\BoletoVerify;
use App\Models\Transaction;
use Carbon\Carbon;

class PagarMeChecker extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pagarme:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checa se o boleto se encontra pago';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $time_start = microtime(true);
        $this->info("Iniciando a checagem");
        $due_date = Carbon::now()->subDays(4)->format('Y-m-d');
        $transactions = Transaction::where('payment_method', 'boleto')->where('status', 'pending')->whereDate('due_date', '>=', $due_date)->get();
        foreach ($transactions as $transaction) {
            $pagarme = json_decode($transaction->getOrder());
//             var_dump($pagarme);
            if ($pagarme->status == 'paid') {
                $transaction->content->update(['status' => 'publicada', 'published_at' => Carbon::now()->format('Y-m-d')]);
                $transaction->content->notifyPositionPublished();
                $transaction->update(['status' => 'paid', 'charge_status' => 'paid']);
               $this->info('Updated');  
            }
        }

       $this->info('not updated');

        $time_elapsed_secs = microtime(true) - $time_start;
        $this->info("Execução:</b> '.$time_elapsed_secs.' segundos");
    }

}
