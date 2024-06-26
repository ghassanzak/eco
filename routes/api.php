<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgetPasswordController;
use App\Http\Controllers\Api\Auth\VerifyController;
use App\Http\Controllers\Api\General\GeneralController;
use App\Http\Controllers\API\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('login',                        [ AuthController::class,'login']);
Route::post('register',                     [AuthController::class, 'register']);

Route::post('forget-password',      [ForgetPasswordController::class, 'forgetPassword']);
Route::post('reset-password',       [ForgetPasswordController::class, 'resetPasswordLoad']);
// Route::post('send-code-mail-reset-pass',    [PasswordController::class, 'sendCodeReset']);
// Route::post('password-reset-form',          [PasswordController::class, 'verificationMail']);

Route::group(['middleware' => ['jwt.auth']], function () {
    
    Route::get('me', [AuthController::class, 'me']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logout',[ AuthController::class,'logout']);
    Route::post('user/changePassword', [AuthController::class, 'changePassword']);

    Route::post('send-code-mail-verify',        [VerifyController::class, 'sendCodeMail']);
    Route::post('/verify-mail',                 [VerifyController::class, 'verificationMail']);


});

Route::get('/all_product',                      [GeneralController::class,'get_product']);
Route::get('/product',                          [GeneralController::class,'show_product']);
Route::get('/all_category',                     [GeneralController::class,'get_category']);
Route::get('/category/{slug}',                  [GeneralController::class,'show_category']);
Route::get('/product/{slug}/review/{id}',       [GeneralController::class,'show_review_product']);

Route::group(['middleware' => ['jwt.auth']], function () {
    Route::post('/user/tags/index',       [TagController::class,'index']);
    Route::post('/user/tags/store',       [TagController::class,'store']);
    Route::post('/user/tags/show',       [TagController::class,'show']);
    Route::post('/user/tags/update',       [TagController::class,'update']);
    Route::post('/user/tags/destroy',       [TagController::class,'destroy']);
});


