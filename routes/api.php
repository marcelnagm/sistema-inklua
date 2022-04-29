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
