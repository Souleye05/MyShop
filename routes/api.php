<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiResponseMiddleware;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Group all routes that need the ApiResponseMiddleware except login
Route::prefix('v1')->middleware(ApiResponseMiddleware::class)->group(function (){
    Route::post('/login', [AuthController::class,'login']); // Login route does not get ApiResponseMiddleware

    Route::controller(ClientController::class)->prefix('clients')->group(function (){
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
        Route::post('/telephone', 'findByPhone');
        Route::get('/telephone', 'findByPhone');
        Route::post('/{id}/dettes', 'listDettes');
        Route::post('/{id}/user', 'showWithUser');
    });

    Route::controller(UserController::class)->prefix('users')->group(function (){
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
        Route::post('/register', 'register');
    });

    Route::controller(ArticleController::class)->prefix('articles')->group(function (){
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'showById');
        Route::post('/libelle', 'showByLibelle');
        Route::patch('/{id}', 'updateStock');
        Route::post('/stock', 'bulkUpdateStock');
    });

    // Route::controller(DetteController::class)->prefix('dettes')->group(function (){
    //     Route::get('/', 'index');
    //     Route::post('/', 'store');
    //     Route::get('/{id}', 'show');
    //     Route::put('/{id}', 'update');
    //     Route::delete('/{id}', 'destroy');
    // });

});
