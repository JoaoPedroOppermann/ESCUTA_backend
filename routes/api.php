<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\UsuarioController;
use App\http\Controllers\PostagemController;
use App\http\Controllers\SeguidorController;
use App\http\Controllers\ComentarioController;
use App\http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Mail\SendMailClient;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

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
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware'=>'auth.jwt'], function () {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::resource('usuarios', UsuarioController::class);
        Route::post('editarImage/{usuario}', [UsuarioController::class, 'editImage']);
        Route::post('validateToken', [AuthController::class, 'validateToken']);
        Route::resource('postagem', PostagemController::class);
        Route::resource('seguidor', SeguidorController::class);
        Route::resource('comentario', ComentarioController::class);
        Route::post('recuperarSenha', [ForgotPasswordController::class, 'reqForgotPassword']);
        Route::post('atualizarSenha', [ForgotPasswordController::class, 'updatePassword']);
        Route::get('buscar', [PostagemController::class, 'show']);
    });