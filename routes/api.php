<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;


Route::group(['prefix' => 'v1', 'middleware' => ['json', 'auth:sanctum']], static function () {
    Route::get('/user', function (Request $request) {
        return ['data' => $request->user()];
    });
});

Route::group(['prefix' => 'v1'], static function () {
    Route::post('/login', [UserController::class, 'login']);
});

