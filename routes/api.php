<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\General\GeneralController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login',[ AuthController::class,'login']);
Route::post('register', [AuthController::class, 'register']);

Route::group(['middleware' => ['jwt.auth']], function () {
    Route::get('my_category',[ UserController::class,'index'])->middleware('auth:api');


    Route::get('me', [AuthController::class, 'me']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logout',[ AuthController::class,'logout']);
    Route::post('user/changePassword', [AuthController::class, 'changePassword']);

});

Route::get('/all_product',                      [GeneralController::class,'get_product']);
Route::get('/product/{slug}',                   [GeneralController::class,'show_product']);
Route::get('/all_category',                     [GeneralController::class,'get_category']);
Route::get('/category/{slug}',                  [GeneralController::class,'show_category']);
Route::get('/product/{slug}/review/{id}',       [GeneralController::class,'show_review_product']);





