<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Content;
use App\Jobs\BoletoVerify;

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
        BoletoVerify::dispatch();

        $time_elapsed_secs = microtime(true) - $time_start;
        $this->info("Execução:</b> '.$time_elapsed_secs.' segundos");
    }

}
