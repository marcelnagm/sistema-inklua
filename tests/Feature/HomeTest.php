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

    private $host;
    private $email;

    
    public function test_home_on() {


        $response = $this->get("/api/home");

        $response->assertStatus(200);
        $response->assertJsonFragment(array( "current_page" => 1));
    }


}
