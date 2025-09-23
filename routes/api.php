<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PostController;


Route::group(['prefix' => 'v1', 'middleware' => ['json', 'auth:sanctum']], static function () {
    Route::get('/user', [UserController::class, 'user']);
    Route::get('/my/posts', [UserController::class, 'myPosts']);
});

Route::group(['prefix' => 'v1'], static function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/posts', [PostController::class, 'index']);
});

