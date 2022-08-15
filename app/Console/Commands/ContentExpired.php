<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Content;
use App\Jobs\PositionExpiredDateVerify;

class ContentExpired extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:expirer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rotina para expiracao da data';

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
        
         PositionExpiredDateVerify::dispatch();
        
        $time_elapsed_secs = microtime(true) - $time_start;
        $this->info("Execução:</b> '.$time_elapsed_secs.' segundos");
    }

}
