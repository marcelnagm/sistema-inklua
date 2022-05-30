<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\cms\UserController;
use App\Http\Controllers\cms\ArticleController;
use App\Http\Controllers\cms\AdController;
use App\Http\Controllers\cms\PositionController;
use App\Http\Controllers\cms\GroupController;
use App\Http\Controllers\cms\ReportController;
use App\Http\Controllers\cms\UserPositionController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\VerificationController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('admin');
});

Route::middleware(checkAdminPermission::class)->get('/admin', function () {
    return view('cms.dashboard');
})->name('dashboard');

Route::get('admin/vagas/importar', [PositionController::class, 'importPositions']);
Route::resource('admin/usuarios/vagas', UserPositionController::class);
Route::resource('admin/usuarios', UserController::class);
Route::resource('admin/artigos', ArticleController::class);
Route::resource('admin/anuncios', AdController::class);
Route::resource('admin/vagas', PositionController::class);
Route::resource('admin/grupo/vagas', GroupController::class);


Route::get('admin/report/inkoins/donation', [ReportController::class, 'searchDonateReport']);
Route::get('admin/report/inkoins/donation/show', [ReportController::class, 'donateReport']);

Route::get('admin/report/inkoins', [ReportController::class, 'searchReport']);
Route::get('admin/report/inkoins/show', [ReportController::class, 'report']);

Route::get('social', function(){
    return view('social');
});

Route::get('/clear-cache', function() {
    echo Artisan::call('config:clear');
    echo Artisan::call('cache:clear');
    echo Artisan::call('config:cache');
});

Route::get('notification', [VerificationController::class, 'show'])->name('verification.notice');
//mepeamento tech


$router->group(['middleware' => ['auth']], function ($router) {
//    $router->post('/admin/candidate', 'App\Http\Controllers\MapeamentoTech\CandidateController@index');
//    $router->post('/admin/candidate/store/', 'App\Http\Controllers\MapeamentoTech\CandidateControler@store');
//    $router->post('/admin/candidate/search', 'App\Http\Controllers\MapeamentoTech\CandidateControler@search');
//    $router->post('/admin/candidate/{id}', 'App\Http\Controllers\MapeamentoTech\CandidateControler@show');
//    $router->post('/admin/candidate/{id}/publish', 'App\Http\Controllers\MapeamentoTech\CandidateControler@publish');
//    $router->put('/admin/candidate/{id}/update', 'App\Http\Controllers\MapeamentoTech\CandidateControler@update');
//    $router->delete('/admin/candidate/{id}', 'App\Http\Controllers\MapeamentoTech\CandidateControler@destroy');

    $router->get('/admin/candidate/clear', 'App\Http\Controllers\MapeamentoTech\CandidateController@clear')->name('candidate.clear');
    $router->get('/admin/candidate/search', 'App\Http\Controllers\MapeamentoTech\CandidateController@search')->name('candidate.search');
    $router->post('/admin/candidate/search/', 'App\Http\Controllers\MapeamentoTech\CandidateController@search')->name('candidate.search');
    $router->get('/admin/candidate/{id}/show', 'App\Http\Controllers\MapeamentoTech\CandidateController@show')->name('tech.show');
    $router->get('/admin/candidate/{id}/publish', 'App\Http\Controllers\MapeamentoTech\CandidateController@publish')->name('tech.publish');
    $router->get('/admin/candidate/{id}/edit', 'App\Http\Controllers\MapeamentoTech\CandidateController@edit')->name('tech.edit');
    $router->get('/admin/candidate/{id}/unpublish', 'App\Http\Controllers\MapeamentoTech\CandidateController@unpublish')->name('tech.unpublish');
    $router->get('/admin/candidate/{gid}/delete', 'App\Http\Controllers\MapeamentoTech\CandidateController@destroy')->name('tech.destroy-me');
    Route::resource('/admin/states', 'App\Http\Controllers\MapeamentoTech\StateController');
    Route::resource('/admin/status', 'App\Http\Controllers\MapeamentoTech\CandidateStatusController');
    Route::resource('/admin/candidate', 'App\Http\Controllers\MapeamentoTech\CandidateController');
    Route::get('/admin/candidate/pcd_report/{id}', 'App\Http\Controllers\MapeamentoTech\CandidateController@pcd_report')->name('candidate.pcd_report');
    $router->post('/candidate/detail/', 'App\Http\Controllers\MapeamentoTech\CandidateController@detail')->name('candidate.detail');
    Route::resource('/admin/english_level', 'App\Http\Controllers\MapeamentoTech\CandidateEnglishLevelController');
    Route::resource('/admin/role', 'App\Http\Controllers\MapeamentoTech\CandidateRoleController');
    
});

//sistema hunting admin


$router->group(['middleware' => ['auth']], function ($router) {
//    $router->post('/admin/hunting/candidate', 'App\Http\Controllers\HuntingAdmin\CandidateController@index');
//    $router->post('/admin/hunting/candidate', 'App\Http\Controllers\HuntingAdmin\CandidateController@index');
//    $router->post('/admin/hunting/candidate/store', 'App\Http\Controllers\HuntingAdmin\CandidateController@store');
    Route::get('/hunting/candidate-hunt/cv/{id}', 'App\Http\Controllers\HuntingAdmin\CandidateController@cv')->name('hunt.cv');
    Route::get('/hunting/candidate-hunt/pcd_report/{id}', 'App\Http\Controllers\HuntingAdmin\CandidateController@pcd_report')->name('hunt.pcd_report');
    Route::get('/hunting/candidate-hunt/clear', 'App\Http\Controllers\HuntingAdmin\CandidateController@clear')->name('hunt.clear');
    Route::resource('/hunting/candidate-hunt', 'App\Http\Controllers\HuntingAdmin\CandidateController')->name('index','hunt.index');
    Route::post('/hunting/candidate-hunt/search', 'App\Http\Controllers\HuntingAdmin\CandidateController@search')->name('hunt.search');    
    Route::resource('/hunting/education/{id}/', 'App\Http\Controllers\HuntingAdmin\CandidateEducationController')->name('create','education.hunt.create');
    Route::resource('/hunting/work/{id}/', 'App\Http\Controllers\HuntingAdmin\CandidateExperienceController')->name('create','work.hunt.create');
    Route::resource('/hunting/education', 'App\Http\Controllers\HuntingAdmin\CandidateEducationController');
    Route::resource('/hunting/work', 'App\Http\Controllers\HuntingAdmin\CandidateExperienceController');
   
    Route::post('/users/search', 'App\Http\Controllers\HuntingAdmin\UserController@search')->name('users.search');
    Route::get('/users/get', 'App\Http\Controllers\HuntingAdmin\UserController@clear')->name('users.clear');
    Route::get('/users/promote/{id}', 'App\Http\Controllers\HuntingAdmin\UserController@promote')->name('users.promote');
    Route::post('/users/grant/{id}', 'App\Http\Controllers\HuntingAdmin\UserController@grant')->name('users.grant');
    Route::get('/users/revoke/{id}', 'App\Http\Controllers\HuntingAdmin\UserController@revoke')->name('users.revoke');
    Route::resource('/users', 'App\Http\Controllers\HuntingAdmin\UserController');
    
});


// rotas carteira

 Route::resource('/inklua_office', 'App\Http\Controllers\Carteira\InkluaOfficeController');
