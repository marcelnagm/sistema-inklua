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

class TransactionTest extends TestCaseComplex {

    private $host;
    private $email;

    public function __construct($name = null, $data = [], $dataName = '') {
        parent::__construct($name, $data, $dataName);
        $this->host = env('APP_URL');
     
//        $this->email = 'luiz_silva@gmail.com';
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_host_on() {


        $response = $this->get("$this->host/admin/login");

        $response->assertStatus(200);
    }
    

    public function test_checker() {

//        $faker = Factory::create();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR')
        ];
        $data = [];
      
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR')
        ];
        $response = $this->get("$this->host/admin/login",$headers);

        $response->assertStatus(200);
        $this->display('Checagem para transações e rotina--- ok!');
    }



}
