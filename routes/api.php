<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Post\PostController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\VerificationController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//   These Are Users Routes

Route::group([ 'middleware' => 'api'] , function ($router){
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login' , [AuthController::class , 'login']);
    Route::post('user-profile' , [AuthController::class , 'profile']);
    Route::delete('deleteUser/{id}' , [AuthController::class , 'destroy']);
    Route::put('updateUser/{id}' , [AuthController::class , 'update']);
    Route::post('logout' , [AuthController::class , 'logout']);
});

Route::post('password/email',[ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('password/reset',[ResetPasswordController::class, 'reset']);

Route::get('email/resend' , [VerificationController::class , 'resend'])->name('verification.resend');
Route::get('email/verify/{id}/{hash}' , [VerificationController::class, 'verify'])->name('verification.verify');

//   These Are Posts Routes

Route::group([] ,function (){
    Route::get('posts' , [PostController::class, 'index']);
    Route::post('post/{id}' , [ PostController::class , 'show']);

    Route::group(['middleware' => 'api'], function(){
        Route::post('createPost', [PostController::class , 'store']);
        Route::put('updatePost',  [PostController::class, 'update']);
        Route::delete('deletePost/{id}', [PostController::class , 'destroy']);

    });
});


