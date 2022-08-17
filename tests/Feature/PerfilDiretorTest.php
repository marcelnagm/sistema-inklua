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

class PerfilDiretorTest extends TestCaseComplex {

    public function test_whoami() {

        $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_DIRETOR')
        ];
        $request = new Request('GET', url('/api/auth/whoami'), $headers);
        $response = $client->sendAsync($request)->wait();
        $data = json_decode($response->getBody(), true);
//        $this->display($data);

        $user = User::find($data['id']);

        $this->assertEquals(true, $user->admin);
    }

    public function test_produtividade() {

        $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_DIRETOR')
        ];
        $request = new Request('GET', url('/api/relatorio/produtividade'), $headers);
        $response = $client->sendAsync($request)->wait();
        $data = json_decode($response->getBody(), true);
//        $this->display($data);
//        $user = User::find($data['id']);
        $this->assertArrayHasKey('recrutadores', $data['data']);
        $this->display('---------');
        $this->display('Relatorio Produtividade - ok ');
    }

    
    public function test_produtividade_filtro_data() {

        $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_DIRETOR')
        ];

        $data_send = [
            'date_start' => '01/06/2022',
            'date_end' => '01/10/2022'
        ];

        $request = new Request('GET', url('/api/relatorio/produtividade') , $headers);
        $response = $client->sendAsync($request, ['query' => $data_send])->wait();
        $data = json_decode($response->getBody(), true);
//        $this->display($data);
//        $user = User::find($data['id']);
        $this->assertArrayHasKey('recrutadores', $data['data']);
        $this->display('Relatorio Produtividade filtro [intervalo data]- ok ');
    }

    public function test_produtividade_filtro_data_office() {

        $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_DIRETOR')
        ];

        $data_send = [
            'date_start' => '01/06/2022',
            'date_end' => '01/10/2022',
            'office' => 1
        ];
        $request = new Request('GET', url('/api/relatorio/produtividade'), $headers);
        $response = $client->sendAsync($request, ['query' => $data_send])->wait();
        $data = json_decode($response->getBody(), true);
//        
//        $this->display($data);
        $office = \App\Models\InkluaOffice::find($data_send['office']);
        $this->assertArrayHasKey('recrutadores', $data['data']);
        foreach ($data['data']['recrutadores'] as $recrutador) {
            $this->assertEquals($office->name, $recrutador['office']);
        }

        $this->display('Relatorio Produtividade filtro [intervalo data,escritorio]- ok ');
//        $user = User::find($data['id']);
    }

    public function test_produtividade_filtro_data_office_recruiter() {

        $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_DIRETOR')
        ];

        $data_send = [
            'date_start' => '01/06/2022',
            'date_end' => '01/10/2022',
            'office' => 2
        ];
        $request = new Request('GET', url('/api/relatorio/produtividade'), $headers);
        $response = $client->sendAsync($request, ['query' => $data_send])->wait();
        $data = json_decode($response->getBody(), true);
//        
//        $this->display($data);
        $office = \App\Models\InkluaOffice::find($data_send['office']);
        $this->assertArrayHasKey('recrutadores', $data['data']);
        $id_test = 0;
        $name_test = '';
        foreach ($data['data']['recrutadores'] as $recrutador) {
            if ($name_test == '') {
                $id_test = $recrutador['id'];
                $name_test = explode(' ', $recrutador['name'])[1];               
            }
            $this->assertEquals($office->name, $recrutador['office']);
        }
        $data_send['recruiter'] = $name_test;
        $request = new Request('GET', url('/api/relatorio/produtividade'), $headers);
        $response = $client->sendAsync($request, ['query' => $data_send])->wait();
        $data = json_decode($response->getBody(), true);
//        $this->display($data);

        foreach ($data['data']['recrutadores'] as $recrutador) {            
            $this->assertStringContainsString($name_test, $recrutador['name']);
        }
        $this->display('Relatorio Produtividade filtro [intervalo data,escritorio,recrutador]- ok ');
//        $user = User::find($data['id']);
    }

    
     public function test_engajamento_filtro_data() {

        $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_DIRETOR')
        ];

        $data_send = [
            'date_start' => '01/06/2022',
            'date_end' => '01/10/2022'
        ];

        $request = new Request('GET', url('/api/relatorio/engajamento') , $headers);
        $response = $client->sendAsync($request, ['query' => $data_send])->wait();
        $data = json_decode($response->getBody(), true);
//        $this->display($data);
//        $user = User::find($data['id']);
        $this->assertArrayHasKey('total_usuarios', $data['data']['geral'],'Verificando total de usuarios - ok');
        $this->assertNotEquals(0, $data['data']['geral']['total_usuarios'],'Verificando contagem total de usuarios - ok');
        $this->display('Relatorio Engajamento filtro [intervalo data]- ok ');
    }
    
}
