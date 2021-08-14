<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\UsuarioController;
use App\http\Controllers\PostagemController;
use App\http\Controllers\SeguidorController;
use App\http\Controllers\ComentarioController;

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
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('postagem', PostagemController::class);
    Route::resource('seguidor', SeguidorController::class);
    Route::resource('comentario', ComentarioController::class);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
