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

class HomeTest extends TestCaseComplex {

    function display($myvar) {

        fwrite(STDERR, print_r($myvar));
    }

    private $host;
    private $email;

    public function __construct($name = null, $data = [], $dataName = '') {
        parent::__construct($name, $data, $dataName);
        $this->host = env('APP_URL');
        $this->email = Str::random('10') . '@gmail.com';
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
    
    public function test_home_on() {


        $response = $this->get("/api/home");

        $response->assertStatus(200);
        $response->assertJsonFragment(array( "current_page" => 1));
    }


}