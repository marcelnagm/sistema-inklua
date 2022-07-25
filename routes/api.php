<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActionsController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\MyContentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TransactionController;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */

Route::get('/auth/whoami', function(Request $request){
       $user = auth()->guard('api')->user();
           return $user;
});

/* Auth */
Route::post('/user/register', [RegisterController::class, 'register']);
Route::post('/user/login', [LoginController::class, 'login']);

/* Social Auth */
Route::post('/auth/{media}', [SocialController::class, 'login'])->where('media', 'facebook|google');

/* Auth - Email Verification */
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/verification', [VerificationController::class, 'resend'])->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');

/* Auth - Password */
Route::post('/user/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/user/password/reset', [ResetPasswordController::class, 'reset']);
Route::post('/user/password/validateReset', [ResetPasswordController::class, 'validateReset']);
Route::post('/user/password/update', [UserController::class, 'updatePassword']);

/* Account */
Route::post('/user/update', [UserController::class, 'update']);
Route::post('/user/delete', [UserController::class, 'delete']);
Route::post('/user/accept_terms', [UserController::class, 'acceptTerms']);

/* Actions */
Route::post('/actions/share', [ActionsController::class, 'share']);
Route::post('/actions/click', [ActionsController::class, 'click']);
Route::post('/actions/donate', [ActionsController::class, 'donate']);
Route::post('/actions/clear', [ActionsController::class, 'clear']);

/* Wallet */
Route::get('/wallet', [WalletController::class, 'wallet']);

/* Notification */
Route::get('/notifications', [NotificationController::class, 'index']);
Route::get('/notifications/{id}', [NotificationController::class, 'update']);

Route::get('/home', [ApiController::class, 'home']);
Route::get('/busca', [ApiController::class, 'search']);
Route::get('/vaga/{id}', [ApiController::class, 'position'])->where('id', '[0-9]+');
Route::get('/minhas-vagas', [MyContentController::class, 'index']);
Route::get('/minhas-vagas/status', [MyContentController::class, 'myContentStatus']);
Route::post('/minhas-vagas/new', [MyContentController::class, 'store']);
Route::get('/minhas-vagas/{id}/edit', [MyContentController::class, 'edit']);
Route::post('/minhas-vagas/{id}', [MyContentController::class, 'update']);
Route::get('/minhas-vagas/details/{id}', [MyContentController::class, 'details']);

// Route::get('/contato', [ApiController::class, 'contact']);
Route::post('/contato', [ApiController::class, 'contact']);

Route::post('/transaction', [TransactionController::class, 'create']);
Route::get('/transaction/order', [TransactionController::class, 'order']);

use App\Http\Middleware\checkUserInkluer;

// rotas novas para aprovacao e cancelamento
Route::group(['middleware' => ['api', 'App\Http\Middleware\checkUserInkluer']], function () {

    Route::get('/vaga/aocliente/{id}', [MyContentController::class, 'clientevaluate']);
    Route::get('/vaga/aprovar/{id}', [MyContentController::class, 'approve']);
    Route::get('/vaga/aprovar/{id}', [MyContentController::class, 'approve']);
    Route::get('/vaga/fechar/{id}', [MyContentController::class, 'close']);
    Route::post('/vaga/cancelar/{id}', [MyContentController::class, 'cancel']);
});

//rotas sistema hunting

use App\Models\PcdType;
use App\Models\State;
use App\Models\User;
use App\Http\Controllers\Hunting\Recruiter\CandidateControler;
use App\Http\Controllers\Hunting\Recruiter\CandidateReportControler;
use App\Http\Controllers\Hunting\Recruiter\CandidateEducationControler;
use App\Http\Controllers\Hunting\Recruiter\CandidateExperienceControler;

Route::group(['middleware' => ['api']], function () {

    Route::get('/admin/hunting/candidate', 'App\Http\Controllers\Hunting\Recruiter\CandidateControler@index');
    Route::get('/admin/hunting/candidate/{id}', 'App\Http\Controllers\Hunting\Recruiter\CandidateControler@show');
    Route::get('/admin/hunting/candidate/cv/{id}', 'App\Http\Controllers\HuntingAdmin\CandidateController@cv')->name('hunt.api.cv');
    Route::get('/admin/hunting/candidate/pcd_report/{id}', 'App\Http\Controllers\HuntingAdmin\CandidateController@pcd_report')->name('hunt.api.pcd_report');
    
    Route::post('/admin/hunting/education', 'App\Http\Controllers\Hunting\Recruiter\CandidateEducationControler@index');

    Route::post('/admin/hunting/work', 'App\Http\Controllers\Hunting\Recruiter\CandidateExperienceControler@index');
    Route::get('/admin/hunting/view-content/{id}', 'App\Http\Controllers\Hunting\Recruiter\SearchControler@moreDetails');

    Route::get('/admin/hunting/report', 'App\Http\Controllers\Hunting\Recruiter\CandidateReportControler@index');
    Route::post('/admin/hunting/report/store/', 'App\Http\Controllers\Hunting\Recruiter\CandidateReportControler@store');
    Route::post('/admin/hunting/report/{id}/update', 'App\Http\Controllers\Hunting\Recruiter\CandidateReportControler@update');
    Route::delete('/admin/hunting/report/{id}', 'App\Http\Controllers\Hunting\Recruiter\CandidateReportControler@destroy');

    Route::post('/admin/hunting/search', 'App\Http\Controllers\Hunting\Recruiter\SearchControler@index_search');

    Route::get('/admin/hunting/job/recruiter/{id}', 'App\Http\Controllers\Hunting\Recruiter\JobLikeControler@index');
    Route::post('/admin/hunting/job/recruiter/{id}/search', 'App\Http\Controllers\Hunting\Recruiter\JobLikeControler@search');
//Route::delete('/job/{id}', 'JobLikeControler@destroy');
     Route::post('/admin/hunting/recruiter/search',function (Request $request){
        return User::searchRecruiter($request); 
     });
    
    
});

Route::group(['middleware' => ['api']], function ($router) {

    Route::get('/candidate/me', 'App\Http\Controllers\Hunting\Candidate\CandidateControler@show');
    Route::post('/candidate/store', 'App\Http\Controllers\Hunting\Candidate\CandidateControler@store');
    Route::put('/candidate/update', 'App\Http\Controllers\Hunting\Candidate\CandidateControler@update');
    Route::delete('/candidate/{id}', 'App\Http\Controllers\Hunting\Candidate\CandidateControler@destroy');

    Route::get('/education', 'App\Http\Controllers\Hunting\Candidate\CandidateEducationControler@index');
    Route::post('/education/store/', 'App\Http\Controllers\Hunting\Candidate\CandidateEducationControler@store');
    Route::post('/education/{id}', 'App\Http\Controllers\Hunting\Candidate\CandidateEducationControler@show');
    Route::put('/education/{id}/update', 'App\Http\Controllers\Hunting\Candidate\CandidateEducationControler@update');
    Route::post('/education/{id}/update', 'App\Http\Controllers\Hunting\Candidate\CandidateEducationControler@update');
    Route::post('/education/{id}/delete', 'App\Http\Controllers\Hunting\Candidate\CandidateEducationControler@destroy');

    Route::get('/work', 'App\Http\Controllers\Hunting\Candidate\CandidateExperienceControler@index');
    Route::post('/work/store/', 'App\Http\Controllers\Hunting\Candidate\CandidateExperienceControler@store');
    Route::post('/work/{id}', 'App\Http\Controllers\Hunting\Candidate\CandidateExperienceControler@show');
    Route::put('/work/{id}/update', 'App\Http\Controllers\Hunting\Candidate\CandidateExperienceControler@update');
    Route::post('/work/{id}/update', 'App\Http\Controllers\Hunting\Candidate\CandidateExperienceControler@update');
    Route::post('/work/{id}/delete', 'App\Http\Controllers\Hunting\Candidate\CandidateExperienceControler@destroy');

    Route::post('/job/like/', 'App\Http\Controllers\Hunting\Candidate\JobLikeControler@store');
    Route::get('/job/exist/', 'App\Http\Controllers\Hunting\Candidate\JobLikeControler@exist');
//Route::delete('/job/{id}', 'App\Http\Controllers\Hunting\Candidate\JobLikeControler@destroy');
});

//Rotas nao protegidas

Route::get('/city', 'App\Http\Controllers\MapeamentoTech\CityControler@index');
Route::get('/city/uf/', 'App\Http\Controllers\MapeamentoTech\CityControler@uf');
Route::get('/city/name/', 'App\Http\Controllers\MapeamentoTech\CityControler@by_name');

Route::get('/state', function () use ($router) {
    return State::all();
});

Route::get('/pcd_type', function () use ($router) {
    return PcdType::all();
});

Route::get('/races', function () use ($router) {
    return App\Models\CandidateRace::all();
});

Route::get('/genders', function () use ($router) {
    return App\Models\CandidateGender::all();
});

Route::get('/level_education', function () use ($router) {
    return App\Models\LevelEducation::all();
});

// Rotas API Mapeamento Tech

$router->post('/search', 'App\Http\Controllers\MapeamentoTech\SearchControler@index_search');
$router->get('/search', 'App\Http\Controllers\MapeamentoTech\SearchControler@index_search');
$router->post('/search_more', 'App\Http\Controllers\MapeamentoTech\SearchControler@search_more');
$router->get('/people/{gid}', 'App\Http\Controllers\MapeamentoTech\SearchControler@candidate');
$router->post('/detail/{gid}', 'App\Http\Controllers\MapeamentoTech\SearchControler@detail');

// Rotas da API Carteira



Route::group(['middleware' => ['api']], function () {

    Route::get('/clients', 'App\Http\Controllers\Carteira\ClientController@all');
    Route::get('/client_conditions', 'App\Http\Controllers\Carteira\ClientConditionController@all');
    Route::post('/carteira', 'App\Http\Controllers\Carteira\ReportController@index');
    Route::get('/escritorios', function () {
        return App\Models\InkluaOffice::where('active',1)->get();
    });
});

//Rotas Perfil Lider
Route::group(['middleware' => ['api']], function () {

    Route::get('/vagas/publicada', 'App\Http\Controllers\Lider\ReportController@index');
    Route::get('/vagas/reposicao', 'App\Http\Controllers\Lider\ReportController@index_repos');
    Route::get('/vagas/fechada', 'App\Http\Controllers\Lider\ReportController@index_fechada');
    Route::get('/vagas/cliente', 'App\Http\Controllers\Lider\ReportController@index_cliente');
    Route::get('/vagas/{id}/descritivo', 'App\Http\Controllers\Lider\ContentController@description')->name('vaga.descritivo');
    Route::get('/vagas/{id}/detalhes', 'App\Http\Controllers\Lider\ContentController@details')->name('vaga.detalhes');
    Route::post('/vagas/{id}/trocar', 'App\Http\Controllers\Lider\ContentController@changeRecruiter');
    Route::post('/vagas/{id}/substituir', 'App\Http\Controllers\Lider\ContentController@replacement');
    Route::post('/vagas/{id}/selects', 'App\Http\Controllers\Lider\ContentController@selects');
    Route::post('/vagas/{id}/aocliente', 'App\Http\Controllers\Lider\ContentController@sendClient');
   
    Route::get('/relatorio/produtividade', 'App\Http\Controllers\Lider\ReportController@index_produtidade');
    Route::get('/relatorio/engajamento', 'App\Http\Controllers\Diretor\ReportController@index_engajamento');
    
});
