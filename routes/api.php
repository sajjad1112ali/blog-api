<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PostController;


Route::group(['prefix' => 'v1', 'middleware' => ['json', 'auth:sanctum']], static function () {
    Route::get('/logout', [UserController::class, 'logout']);
    Route::get('/user', [UserController::class, 'user']);
    Route::group(['prefix' => 'posts'], static function () {
        Route::get('/my', [UserController::class, 'myPosts']);
        Route::post('', [PostController::class, 'createPost']);
    });
    Route::group(['prefix' => 'posts/{post}'], static function () {
        Route::put('', [PostController::class, 'updatePost']);
        Route::delete('', [PostController::class, 'deletePost']);
        Route::post('react', [PostController::class, 'react']);
    });
});

Route::group(['prefix' => 'v1'], static function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
    Route::group(['prefix' => 'posts'], static function () {
        Route::get('', [PostController::class, 'index']);
        Route::get('/{post}', [PostController::class, 'singlePost']);
    });
});
