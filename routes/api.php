<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\ReviewController;
use App\Http\Controllers\Api\Admin\TagController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgetPasswordController;
use App\Http\Controllers\Api\Auth\VerifyController;
use App\Http\Controllers\Api\General\GeneralController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('login',                        [ AuthController::class,'login']);
Route::post('register',                     [AuthController::class, 'register']);

Route::post('forget-password',      [ForgetPasswordController::class, 'forgetPassword']);
Route::post('reset-password',       [ForgetPasswordController::class, 'resetPasswordLoad']);

Route::group(['middleware' => ['jwt.auth']], function () {
    
Route::get('me',                        [AuthController::class, 'me']);
    Route::post('refresh',                  [AuthController::class, 'refresh']);
    Route::post('logout',                   [ AuthController::class,'logout']);
    Route::post('user/changePassword',      [AuthController::class, 'changePassword']);

    Route::post('send-code-mail-verify',    [VerifyController::class, 'sendCodeMail']);
    Route::post('/verify-mail',             [VerifyController::class, 'verificationMail']);

Route::post('/user/tag/index',     [TagController::class,'index']);
    Route::post('/user/tag/store',     [TagController::class,'store']);
    Route::post('/user/tag/show',      [TagController::class,'show']);
    Route::post('/user/tag/update',    [TagController::class,'update']);
    Route::post('/user/tag/destroy',   [TagController::class,'destroy']);

Route::post('/user/category/index',     [CategoryController::class,'index']);
    Route::post('/user/category/store',     [CategoryController::class,'store']);
    Route::post('/user/category/show',      [CategoryController::class,'show']);
    Route::post('/user/category/update',    [CategoryController::class,'update']);
    Route::post('/user/category/destroy',   [CategoryController::class,'destroy']);

Route::post('/user/product/index',          [ProductController::class,'index']);
    Route::post('/user/product/store',          [ProductController::class,'store']);
    Route::post('/user/product/show',           [ProductController::class,'show']);
    Route::post('/user/product/update',         [ProductController::class,'update']);
    Route::post('/user/product/destroy',        [ProductController::class,'destroy']);
    Route::post('/user/product/remove-image',   [ProductController::class,'removeImage']);

Route::post('/user/review/index',          [ReviewController::class,'index']);
    Route::post('/user/review/store',          [ReviewController::class,'store']);
    Route::post('/user/review/show',           [ReviewController::class,'show']);
    Route::post('/user/review/update',         [ReviewController::class,'update']);
    Route::post('/user/review/destroy',        [ReviewController::class,'destroy']);

});

Route::get('/all_product',                      [GeneralController::class,'get_product']);
Route::get('/product',                          [GeneralController::class,'show_product']);
Route::get('/all_category',                     [GeneralController::class,'get_category']);
Route::get('/category/{slug}',                  [GeneralController::class,'show_category']);
Route::get('/product/{slug}/review/{id}',       [GeneralController::class,'show_review_product']);




