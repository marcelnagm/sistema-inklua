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

class HuntingStateCityTest extends TestCaseComplex {

  
    private $jwt;

    public function __construct($name = null, $data = [], $dataName = '') {
        parent::__construct($name, $data, $dataName);
        $this->jwt = env('APP_JWT_RECRUTADOR');

//        $this->email = 'luiz_silva@gmail.com';
    }

    public function test_whoami() {

        $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR')
        ];
        $request = new Request('GET', url('/api/auth/whoami'), $headers);
        $response = $client->sendAsync($request)->wait();
        $data = json_decode($response->getBody(), true);
//        $this->display($data['inkluer']);
//        $this->assertArrayHasKey($key, $headers, $message)
        $this->assertArrayHasKey('inkluer', $data);
        $this->assertEquals(true, $data['inkluer']);
    }

    /**
     * @depends test_whoami
     */
    public function test_states() {

        $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR')
        ];
        $response = $this->get(url("/api/state/"), array(), $headers);
        $response->assertStatus(200);
        $this->display('');
        $this->display('Listagem de Estados--- ok!');
    }
    
    /**
     * @depends test_whoami
     */
    public function test_city() {

        $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR')
        ];
        $response = $this->get(url("/api/city/"), array(), $headers);
        $response->assertStatus(200);
        $this->display('Listagem de Cidades--- ok!');
    }
    
    /**
     * @depends test_whoami
     */
    public function test_city_busca() {

        $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR')
        ];
        $response = $this->get(url("/api/city/"), array("id" => "Acre",
    "uf" => 1), $headers);
        $response->assertStatus(200);
        $data = $response->json();
//        $this->display($response->json());
        $this->assertArrayHasKey('name',$data['data'][0]);
        $this->assertEquals('Acrelndia', $data['data'][0]['name']);
        $this->display('Busca de Cidades--- ok!');          
    }

}
