<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Models\Dette;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function (){
    // Route::controller(AuthController::class)->prefix('auth')->group(function (){
    //     Route::post('/login', 'login');
    // });
    Route::post('/login', [AuthController::class,'login']);
    Route::controller(ClientController::class)->prefix('clients')->group(function (){
        Route::get('/','index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
        Route::post('/telephone',  'findByPhone');
        Route::get('/telephone',  'findByPhone');
        Route::post('/{id}/dettes', 'listDettes');
        Route::post('/{id}/user', 'showWithUser');
    });

    Route::controller(UserController::class)->prefix('users')->group(function (){
        Route::get('/','index');
        Route::post('/', 'store');
        // Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
        
        Route::post('/register', 'register');
    });

    Route::controller(ArticleController::class)->prefix('articles')->group(function (){
        Route::get('/','index');
        Route::post('/', 'store');
        Route::get('/{id}', 'showById');
        Route::get('/libelle', 'showByLibelle');
        Route::patch('/{id}', 'updateStock');
        Route::post('/stock', 'bulkUpdateStock');
    });

    // Route::controller(DetteController::class)->prefix('dettes')->group(function (){
    //     Route::get('/','index');
    //     Route::post('/', 'store');
    //     Route::get('/{id}', 'show');
    //     Route::put('/{id}', 'update');
    //     Route::delete('/{id}', 'destroy');
    // });


});
