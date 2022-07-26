<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCaseComplex;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\LoginController;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use App\Models\Content;

class HomeMyContentExtTest extends TestCaseComplex {

  
    private $jwt;
    private $id;

    public function __construct($name = null, $data = [], $dataName = '') {
        parent::__construct($name, $data, $dataName);
        $this->jwt = env('APP_JWT_RECRUTADOR_EXTERNO');

//        $this->email = 'luiz_silva@gmail.com';
    }

    public function test_whoami() {

        $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR_EXTERNO')
        ];
        $request = new Request('GET', url('/api/auth/whoami'), $headers);
        $response = $client->sendAsync($request)->wait();
        $data = json_decode($response->getBody(), true);
//        $this->display($data['inkluer']);
//        $this->assertArrayHasKey($key, $headers, $message)
        $this->assertArrayHasKey('inkluer', $data);
        $this->assertEquals(false, $data['inkluer']);
    }

    /**
     * @depends test_whoami
     */
    public function test_minhas_vagas() {

        $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR_EXTERNO')
        ];
        $request = new Request('GET', url('/api/minhas-vagas'), $headers);
        $response = $client->sendAsync($request)->wait();
        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('myContents', $data);
    }

    private $data_vaga = [
        'title' => 'Repositor de mercadorias',
        'salary' => '1850.50',
        'image' => 'https://inklua.com.br/2DCVQ4jDTGA5rp6uhD0xSi8Zw2q4cz7O1KjbuFp.jpg',
        'state' => 'SC',
        'city' => 'Blumenau',
        'district' => 'Centro',
        'description' => 'Realizar o abastecimento das gondolas, preencher relatórios de vendas, preencher ralatório  de troca de mercadorias, atendimento ao cliente.',
        'benefits' => 'Vale refeição de R$ 30,00 por dia.',
        'requirements' => 'Experiência mínima de 3 anos como repositor',
        'hours' => 'Seg à sex, da 08:30 às 17:30 hrs',
        'english_level' => '1',
        'observation' => 'O candidato não pode ter limitação de levantar peso.'
    ];

    /**
     * @depends test_whoami    
     */
    public function test_cadastrar_vaga() {

        $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR_EXTERNO')
        ];
        $data_vaga_ok = array_merge($this->data_vaga, array('client_condition_id' => 1, 'client_id' => 1, 'vacancy' => 3));
//        $request = new Request('POST', url('/api/minhas-vagas/new'), $headers);
        $response = $this->post(url('/api/minhas-vagas/new'), $data_vaga_ok, $headers);
//        $response = $client->sendAsync($request, $this->data_vaga)->wait();
        $data = $response->json();
//        $this->display($data);
        $this->assertArrayHasKey('id', $data);
        $this->atualizar_vaga($data['id']);
    }

    /**
     * @depends test_whoami  
     * @depends test_cadastrar_vaga
     */
    public function atualizar_vaga($id) {

        $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR_EXTERNO')
        ];
        $data_vaga_ok = array_merge($this->data_vaga, array('client_condition_id' => 1, 'client_id' => 1, 'vacancy' => 19, 'district' => 'teste update'));
//        $request = new Request('POST', url('/api/minhas-vagas/id'), $headers);
        $response = $this->post(url("/api/minhas-vagas/".$id), $data_vaga_ok, $headers);
//        $response = $client->sendAsync($request, $this->data_vaga)->wait();
        $data = $response->json();
//        $this->display($data);
        $response->assertStatus(400);
        $content = Content::find($id);
        $content->status= 'reprovada';
        $content->save();
        $response = $this->post(url("/api/minhas-vagas/$id"), $data_vaga_ok, $headers);
//        $response = $client->sendAsync($request, $this->data_vaga)->wait();
        $data = $response->json();
//        $this->display($data);
        $this->assertArrayHasKey('district', $data);
        $this->assertEquals('teste update', $data['district']);
        $this->id = $id;
        
    }

    /**
     * @depends test_whoami
     * @depends test_cadastrar_vaga
     */
    public function cancelar_vaga_no_reason($id) {

        $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR_EXTERNO')
        ];
//         $request = new Request('POST', url('/api/vaga/aprovar/'), $headers);
        $response = $this->post(url("/api/vaga/cancelar/$this->id"), array(), $headers);
        $data = $response->json();
//        $this->display($data);
        $this->assertArrayHasKey("status", $data);
        $this->assertEquals(1, $data["error"]);
    }

    /**
     * @depends test_whoami
     * @depends test_cadastrar_vaga
     */
    public function cancelar_vaga($id) {

        $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR_EXTERNO')
        ];
//         $request = new Request('POST', url('/api/vaga/aprovar/'), $headers);
        $response = $this->post(url("/api/vaga/cancelar/$this->id"), array('reason' => 'desisti da vaga'), $headers);
        $data = $response->json();
//        $this->display($data);
        $this->assertArrayHasKey("status", $data);
        $this->assertEquals(1, $data["status"]);
    }

}
