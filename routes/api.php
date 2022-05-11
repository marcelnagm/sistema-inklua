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

/* Auth */
Route::post('/user/register', [RegisterController::class, 'register']);
Route::post('/user/login', [LoginController::class, 'login']);

/* Social Auth */
Route::post('/auth/{media}', [SocialController::class, 'login'])->where('media', 'facebook|google');

/* Auth - Email Verification */
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify'); 
Route::post('email/verification', [VerificationController::class, 'resend'])->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');


/* Auth - Password */
Route::post('/user/password/email',[ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/user/password/reset',[ResetPasswordController::class, 'reset']);
Route::post('/user/password/validateReset',[ResetPasswordController::class, 'validateReset']);
Route::post('/user/password/update',[UserController::class, 'updatePassword']);

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

Route::get('/home', [ApiController::class, 'home']);
Route::get('/busca', [ApiController::class, 'search']);
Route::get('/vaga/{id}', [ApiController::class, 'position'])->where('id', '[0-9]+');

// Route::get('/contato', [ApiController::class, 'contact']);
Route::post('/contato', [ApiController::class, 'contact']);



//rotas sistema hunting

use App\Models\PcdType;
use App\Models\State;

use App\Http\Controllers\Recruiter\CandidateControler;
use App\Http\Controllers\Recruiter\CandidateReportControler;
use App\Http\Controllers\Recruiter\CandidateEducationControler;
use App\Http\Controllers\Recruiter\CandidateExperienceControler;

Route::group(['middleware' => ['api']], function () {

    Route::post('/admin/hunting/candidate', 'App\Http\Controllers\Recruiter\CandidateControler@index');
    Route::post('/admin/hunting/candidate/{id}', 'App\Http\Controllers\Recruiter\CandidateControler@show');

    Route::post('/admin/hunting/education', 'App\Http\Controllers\Recruiter\CandidateEducationControler@index');

    Route::post('/admin/hunting/work', 'App\Http\Controllers\Recruiter\CandidateExperienceControler@index');

    Route::post('/admin/hunting/report', 'App\Http\Controllers\Recruiter\CandidateReportControler@index');
    Route::post('/admin/hunting/report/store/', 'App\Http\Controllers\Recruiter\CandidateReportControler@store');
    Route::put('/admin/hunting/report/{id}/update', 'App\Http\Controllers\Recruiter\CandidateReportControler@update');
    Route::delete('/admin/hunting/report/{id}', 'App\Http\Controllers\Recruiter\CandidateReportControler@destroy');

    Route::post('/admin/hunting/search', 'App\Http\Controllers\Recruiter\SearchControler@index_search');

    Route::post('/admin/hunting/job/recruiter/{id}', 'App\Http\Controllers\Recruiter\JobLikeControler@index');
//Route::delete('/job/{id}', 'JobLikeControler@destroy');
 
});

Route::group(['middleware' => ['api']], function ($router) {

    Route::post('/candidate/store/', 'App\Http\Controllers\Candidate\CandidateControler@store');
    Route::put('/candidate/{id}/update', 'App\Http\Controllers\Candidate\CandidateControler@update');
    Route::delete('/candidate/{id}', 'App\Http\Controllers\Candidate\CandidateControler@destroy');

    Route::post('/education', 'App\Http\Controllers\Candidate\CandidateEducationControler@index');
    Route::post('/education/store/', 'App\Http\Controllers\Candidate\CandidateEducationControler@store');
    Route::post('/education/{id}', 'App\Http\Controllers\Candidate\CandidateEducationControler@show');
    Route::put('/education/{id}/update', 'App\Http\Controllers\Candidate\CandidateEducationControler@update');
    Route::delete('/education/{id}', 'App\Http\Controllers\Candidate\CandidateEducationControler@destroy');

    Route::post('/work', 'App\Http\Controllers\Candidate\CandidateExperienceControler@index');
    Route::post('/work/store/', 'App\Http\Controllers\Candidate\CandidateExperienceControler@store');
    Route::post('/work/{id}', 'App\Http\Controllers\Candidate\CandidateExperienceControler@show');
    Route::put('/work/{id}/update', 'App\Http\Controllers\Candidate\CandidateExperienceControler@update');
    Route::delete('/work/{id}', 'App\Http\Controllers\Candidate\CandidateExperienceControler@destroy');

    Route::post('/job/like/', 'App\Http\Controllers\Candidate\JobLikeControler@store');
    Route::get('/job/exist/', 'App\Http\Controllers\Candidate\JobLikeControler@exist');
//Route::delete('/job/{id}', 'App\Http\Controllers\Candidate\JobLikeControler@destroy');
});

//Rotas nao protegidas

Route::post('/city', 'App\Http\Controllers\MapeamentoTech\CityControler@index');
Route::post('/city/uf/', 'App\Http\Controllers\MapeamentoTech\CityControler@uf');
Route::post('/city/name/', 'App\Http\Controllers\MapeamentoTech\CityControler@by_name');


Route::post('/state', function () use ($router) {
    return State::all();
});

Route::post('/pcd_type', function () use ($router) {
    return PcdType::all();
});
