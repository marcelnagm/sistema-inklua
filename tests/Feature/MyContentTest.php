<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\LoginController;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class MyContentTest extends TestCase {

    function display($myvar) {

        fwrite(STDERR, print_r($myvar));
    }

    private $jwt;

    public function __construct($name = null, $data = [], $dataName = '') {
        parent::__construct($name, $data, $dataName);
        $this->jwt =  env('APP_JWT');

//        $this->email = 'luiz_silva@gmail.com';
    }

    public function test_minhas_vagas() {

        $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer '.env('APP_JWT')
        ];
        $request = new Request('GET', url('/api/minhas-vagas'), $headers);
        $response = $client->sendAsync($request)->wait();
        $data = json_decode($response->getBody(),true);
        $this->assertArrayHasKey('myContents', $data );
        
        
    }
    public function test_whoami() {

        $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer '.env('APP_JWT')
        ];
        $request = new Request('GET', url('/api/auth/whoami'), $headers);
        $response = $client->sendAsync($request)->wait();
         $data = json_decode($response->getBody(),true);
//        $this->assertArrayHasKey($key, $headers, $message)
           $this->assertArrayHasKey('email', $data );
    }

}
