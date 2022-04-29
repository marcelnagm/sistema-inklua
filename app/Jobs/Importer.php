<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Content;

class Importer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $signature = 'positions:update';
    protected $description = 'atualiza os positions da base';

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
        $time_start = microtime(true); 

        echo "<p>Importer (Job)</p>";
        $ch = curl_init();
        // IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
        // in most cases, you should set it to true
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'https://ats.compleo.com.br/oportunidades/Inklua/get/json/vagas');
        $result = curl_exec($ch);
        if(!$result){
            echo 'Erro ao buscar dados no completo (curl - https://ats.compleo.com.br/oportunidades/Inklua/get/json/vagas): ' . curl_error($ch);
            return;
        }
        curl_close($ch);

        $apiPositions = json_decode($result);
        Content::where('type', 1)->update(['in_compleo' => 0]);

        $counter = 0;

        foreach($apiPositions as $apiPosition){
            
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
            ]);
            $counter ++;
        }
        $deleted =  Content::where('type', 1)->where('in_compleo', 0)->count();
        Content::where('type', 1)->where('in_compleo', 0)->delete();

        echo "<p>${counter} vagas importadas/atualizadas</p>";
        echo "<p>${deleted} vagas removidas</p>";

        $time_elapsed_secs = microtime(true) - $time_start;
        echo '<p><b>Execução:</b> '.$time_elapsed_secs.' segundos</p>';
    }
}
