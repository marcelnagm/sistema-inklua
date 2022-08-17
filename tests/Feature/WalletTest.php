<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCaseComplex;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use App\Models\User;

class WalletTest extends TestCaseComplex {

    public function test_exist_wallet() {

//        $faker = Factory::create();
//        echo $this->jwt;

        $data = [];

//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_CANDIDATO')
        ];
        $response = $this->get(url('/api/wallet'), $headers);        
        $data = $response->json();
//        $this->display($data);
        $this->assertArrayHasKey("inkoins", $data);
        $response->assertStatus(200);
        $this->display('Checagem para wallet--- ok!');
    }

}
