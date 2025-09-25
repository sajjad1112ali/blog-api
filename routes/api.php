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
        Route::put('{post}', [PostController::class, 'updatePost']);
        Route::delete('{post}', [PostController::class, 'deletePost']);
    });
});

Route::group(['prefix' => 'v1'], static function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/posts', [PostController::class, 'index']);
});
