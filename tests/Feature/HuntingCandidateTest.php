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
use App\Models\Content;

class HuntingCandidateTest extends TestCase {

    function display($myvar) {

        fwrite(STDERR, print_r($myvar));
    }

    private $jwt;
    public $token;

    public function __construct($name = null, $data = [], $dataName = '') {
        parent::__construct($name, $data, $dataName);
//        $this->jwt = env('APP_JWT_RECRUTADOR');

//        $this->email = 'luiz_silva@gmail.com';
    }

    
    public function test_register_pf() {

         $data = [
     "name" => "Luiz",
	"lastname" => "Silva",
	"email" => Str::random(15).'@gmail.com',
	"password" => "123456789",
	"password_confirmation" => "123456789",
	"accepted_terms" => true
    ];
         
        $client = new Client();
//        echo $this->jwt;
        $headers = [
//            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR')
        ];
         $response = $this->post(url('/api/user/register'), $data, $headers);
        $data = $response->json();
        $this->display($data);
//        echjo
//        $this->assertArrayHasKey($key, $headers, $message)
        $this->assertArrayHasKey('user', $data);
        $this->assertEquals('PF', $data['user']['type']);
//        $this->token = $data['token'];
//        session('token',$data['token']);
        $this->test_register_as_candidate($data['token']);
    }
    
     /**
     * @depends test_register_pf
     */
     public function test_register_as_candidate($token) {
         $data = array(    "name" => "Luiz",        
        "surname" => "Silva",
        "cellphone" => "11988772211",
        "email" => "luiz_silva@gmail.com",
        "payment"=> "2500.00",
        "english_level" => 2,
        "portifolio_url" => "https://luiz.com.br",
        "linkedin_url" => "https://linkedin.com/luiz_silva",
        "pcd" => 1,
        "pcd_type_id" => 1,
        "pcd_details" => "não possu globo ocular direito",
        "pcd_report_ext" => "jpg",
        "cv_path_ext" => "jpg",        
        "state_id" => 1,
        "city_id" => 1,
        "remote" => 1,
        "move_out" => 1,
        "gender_id" => 1,
        "race_id" => 1,
        "first_job" => 1,
        "birth_date" => "01/01/1990"
        );
         
         $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' .$token
        ];
         $response = $this->post(url('/api/candidate/store'), $data, $headers);
        $data = $response->json();
        $this->display($data);  
        $this->display($data['msg']);  
        $this->assertEquals('Candidadte successfully added!',$data['msg']);
     }

  

}
