<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Content;

class CompleoImporter extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compleo:importer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data from compleo';

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
        $this->info("Iniciando a importação");

        $ch = curl_init();
        // IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
        // in most cases, you should set it to true
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'https://ats.compleo.com.br/oportunidades/Inklua/get/json/vagas');
        $result = curl_exec($ch);
        if (!$result) {
            $this->error('Erro ao buscar dados no completo (curl - https://ats.compleo.com.br/oportunidades/Inklua/get/json/vagas): ' . curl_error($ch));

            return;
        }
        curl_close($ch);

        $apiPositions = json_decode($result);
        Content::where('type', 1)->update(['in_compleo' => 0]);

        $counter = 0;

        foreach ($apiPositions as $apiPosition) {

            Content::updateOrCreate([
                'compleo_code' => $apiPosition->codigovaga
                    ],
                    [
                        'type' => 1,
                        'in_compleo' => 1,
                        'title' => trim($apiPosition->titulo),
                        'date' => trim($apiPosition->dataabertura),
                        'url' => trim($apiPosition->url),
                        'city' => trim($apiPosition->cidade),
                        'state' => trim($apiPosition->estado),
                        'cod_filial' => trim($apiPosition->codigofilial),
                        'name_filial' => trim($apiPosition->nomefilial),
                        'description' => trim($apiPosition->descricaovaga),
                        'english_level' => 1
            ]);
            $counter++;
        }
        $deleted = Content::where('type', 1)->where('in_compleo', 0)->count();
        Content::where('type', 1)->where('in_compleo', 0)->delete();
        $this->info("${counter} vagas importadas/atualizadas");
        $this->info("${deleted} vagas removidas");

        $time_elapsed_secs = microtime(true) - $time_start;
        $this->info("Execução:</b> '.$time_elapsed_secs.' segundos");
    }

}
