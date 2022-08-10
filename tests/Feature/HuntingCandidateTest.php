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
use Faker\Factory;

class HuntingCandidateTest extends TestCaseComplex {

    private $jwt;
    public $email;
    public $token;

    public function __construct($name = null, $data = [], $dataName = '') {
        parent::__construct($name, $data, $dataName);
//        $this->jwt = env('APP_JWT_RECRUTADOR');
        $this->email = Str::random(15) . '@gmail.com';
//        $this->email = 'luiz_silva@gmail.com';
    }

    public function test_register_pf() {

        $data = [
            "name" => "Luiz",
            "lastname" => "Silva",
            "email" => $this->email,
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
        $data_response = $response->json();
//        $this->display($data);
//        $this->assertArrayHasKey($key, $headers, $message)
        $this->assertArrayHasKey('user', $data_response);
        $this->assertEquals('PF', $data_response['user']['type']);
        $this->token = $data_response['token'];
//        session('token',$data['token']);
        $this->register_as_candidate($data_response['token'], $this->email);
    }

    /**
     * @depends test_register_pf
     */
    public function register_as_candidate($token, $email) {
        $data_2 = array("name" => "Luiz",
            "surname" => "Silva",
            "cellphone" => "11988772211",
            "email" => $this->email,
            "payment" => "2500.00",
            "english_level" => 2,
            "portifolio_url" => "https://luiz.com.br",
            "linkedin_url" => "https://linkedin.com/luiz_silva",
            "pcd" => 1,
            "pcd_type_id" => 1,
            "pcd_details" => "não possu globo ocular direito",
            "state_id" => random_int(1, 27),
            "city_id" => random_int(1, 5507),
            "remote" => random_int(0, 1),
            "hybrid" => random_int(0, 1),
            "presential" => random_int(0, 1),
            "move_out" => random_int(0, 1),
            "gender_id" => 1,
            "race_id" => 1,
            "first_job" => random_int(0, 1),
            "birth_date" => $this->random_date()
        );

        $client = new Client();
//        echo $token;
        $headers = [
            'Authorization' => 'Bearer ' . $token
        ];
        $response = $this->post(url('/api/candidate/store'), $data_2, $headers);
        $data = $response->json();
//        $this->display($data);  

        $response->assertStatus(200);
        $this->assertArrayHasKey('msg', $data);
        $cand = \App\Models\CandidateHunting::where('email', $this->email);

        $this->display('-----------');

//        $this->display($cand->get()->count());
        $this->assertTrue($cand->get()->count() == 1);
        $this->display('Candidato Cadastrando correto!');
        $this->add_candidate_education($token);
        $this->display('Candidato Cadastrando Education correto!');       
        $this->add_candidate_experiece($token);
        $this->display('Candidato Cadastrando Work correto!');
        $this->add_job_like($token);
        $this->display('Candidato Cadastrando joblike!');
    }

    public function add_candidate_education($token) {

        $faker = Factory::create();
        $client = new Client();
//        echo $token;
        $headers = [
            'Authorization' => 'Bearer ' . $token
        ];
//        for($i = random_int(0, 5); $i >=0 ;$i--){

        $data = ["level_education_id" => random_int(1, 6),
            "institute" => $faker->title,
            "course" => $faker->country,
            "start_at" => $this->random_date(),
            "end_at" => $this->random_date()];

        $response = $this->post(url('/api/education/store'), $data, $headers);
        $data = $response->json();
       

        $response->assertStatus(200);
        $this->assertArrayHasKey('status', $data);
        $this->assertTrue($data['status'] == true);
    }
    
    public function add_candidate_experiece($token) {

        $faker = Factory::create();
        $client = new Client();
//        echo $token;
        $headers = [
            'Authorization' => 'Bearer ' . $token
        ];
//        for($i = random_int(0, 5); $i >=0 ;$i--){

        $data = [
            "role" => $faker->jobTitle,
    "company" => $faker->company,
    "description" => $faker->paragraph,
    "start_at" => $this->random_date(),  
    "end_at" => $this->random_date()
            
            
        ];

        $response = $this->post(url('/api/work/store'), $data, $headers);
        $data = $response->json();
//        $this->display($data);  

        $response->assertStatus(200);
        $this->assertArrayHasKey('status', $data);
        $this->assertTrue($data['status'] == true);
    }
    
    public function add_job_like($token) {

        $faker = Factory::create();
        $client = new Client();
//        echo $token;
        $headers = [
            'Authorization' => 'Bearer ' . $token
        ];
//        for($i = random_int(0, 5); $i >=0 ;$i--){

        $data = [
            "job_id" => $faker->randomElement(Content::where('type',1)->where('status','publicada')->pluck('id')),
        ];

        $response = $this->post(url('/api/job/like'), $data, $headers);
        $data = $response->json();
//        $this->display($data);  

        $response->assertStatus(200);
        $this->assertArrayHasKey('status', $data);
        $this->assertTrue($data['status'] == true);
    }

}
