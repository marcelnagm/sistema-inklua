<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCaseComplex;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\LoginController;
use GuzzleHttp\Client;
use App\Models\CandidateHunting;
use GuzzleHttp\Psr7\Request;
use App\Models\Content;
use Faker\Factory;
use App\Models\User;
use App\Models\JobLike;

class HuntingRecruiterTest extends TestCaseComplex {

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

        $this->joblike_all($data['id']);
        $this->display('');
        $this->display('Listagem de Joblike --- ok!');
        $this->report_store($data['id']);
    }

    public function test_candidate_show() {

        $faker = Factory::create();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR')
        ];
        $data = [];
        $gid = CandidateHunting::inRandomOrder()->first()->gid;
        $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR')
        ];
        $request = new Request('GET', url('/api/admin/hunting/candidate/' . $gid), $headers);
        $response = $client->sendAsync($request)->wait();
        $data = json_decode($response->getBody(), true);
//        $this->display($data);
        $this->assertArrayHasKey('id', $data);
        $this->display('Rota para exibição de candidato--- ok!');
    }

    public function joblike_all($me_id) {
        $faker = Factory::create();

        $data = [];

        $content = Joblike::whereIn('job_id', User::find($me_id)->contents()->get()->pluck('id'))->pluck('job_id');
//        $this->display($content);
        $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR')
        ];
        $id = $faker->randomElement($content);

        $request = new Request('GET', url('/api/admin/hunting/job/recruiter/' . $id), $headers);
        $response = $client->sendAsync($request)->wait();
        $data = json_decode($response->getBody(), true);
//        $this->display($data);
        $this->assertArrayHasKey('likes', $data['data']);
    }

    public function report_store($me_id) {
        $faker = Factory::create();
        $content = Joblike::whereIn('job_id', User::find($me_id)->contents()->get()->pluck('id'))->pluck('job_id');
        $id = $faker->randomElement($content);

        $client = new Client();
//        echo $this->jwt;
        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR')
        ];
        $id = $faker->randomElement($content);
        $candidate_id = CandidateHunting::inRandomOrder()->first()->id;
        $data = [
            'job_id' => $id,
            'candidate_id' => $candidate_id
        ];

        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR')
        ];
        $response = $this->post(url('/api/admin/hunting/report/store'), $data, $headers);
        $data_response = $response->json();
//        $this->display($data_response);
        $this->assertArrayHasKey('data', $data_response);
        $this->display('Inicio de abordagem --- ok!');
        $this->report_update_hired($data_response['data']['id'], $id);
        $this->report_update_replacement($data_response['data']['id'], $id);
    }

    public function report_update_hired($report_id, $id) {

        $data = [
            'id' => $report_id,
            'hired' => 1,
            'start_at' => $this->random_date(),
            'report_status_id' => 'hired'
        ];

        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR')
        ];
        $cc = Content::find($id)->contentclient();
        $hired_b = $cc->hired;

        $response = $this->post(url('/api/admin/hunting/report/store'), $data, $headers);
        $data_response = $response->json();
//        $this->display($data_response);
        $this->assertArrayHasKey('data', $data_response);
        $this->assertEquals('hired', $data_response['data']['report_status']);
        $cc = Content::find($id)->contentclient();
        $this->assertEquals($hired_b + 1, $cc->hired);
        $this->display('Contratação ok com contagem ok --- ok!');
    }

    public function report_update_replacement($report_id, $id) {

        $data = [
            'id' => $report_id,
            'hired' => 0,
            'start_at' => null,
            'report_status_id' => 'replacement'
        ];

        $cc = Content::find($id)->contentclient();
        $hired_b = $cc->replaced;

        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR')
        ];
        $response = $this->post(url('/api/admin/hunting/report/store'), $data, $headers);
        $data_response = $response->json();
//        $this->display($data_response);
        $this->assertArrayHasKey('data', $data_response);
        $this->assertEquals('replacement', $data_response['data']['report_status']);
        $cc = Content::find($id)->contentclient();
        $this->assertEquals($hired_b + 1, $cc->replaced);
        $this->report_exists($data_response['data']['id'], $id);
        $this->display('reposição com contagem --- ok!');
    }

    public function report_exists($report_id, $id) {

        $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR')
        ];

        $data = [
            'job_id' => $id
        ];
        $response = $this->get(url('/api/admin/hunting/report'), $data, $headers);
        $data_response = $response->json();
//        $this->display($data_response);
        foreach ($data_response['data']['reports'] as $report) {
            if ($report['id'] == $report_id) {
                $this->assertEquals('replacement', $report['report_status']);
                break;
            }
        }

        $this->display('Checagem se os report existe para o job informado --- ok!');
        $this->details_work($id);
    }

    public function details_work($id) {
        $data = [];
           $headers = [
            'Authorization' => 'Bearer ' . env('APP_JWT_RECRUTADOR')
        ];
        $response = $this->get(url("/api/admin/hunting/view-content/$id"), $data, $headers);
        $data_response = $response->json();
        
        $this->assertArrayHasKey('listing', $data_response['data']);
           
        $this->display('Detalhes da vaga--- ok!');
    }

}
