<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\EditeursController;
use App\Http\Controllers\JeuController;
use App\Http\Controllers\MecaniquesController;
use App\Http\Controllers\ThemesController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

Route::post('/commentaires', [CommentaireController::class, 'store'])->middleware('api') ;
Route::delete('/commentaires/{id}', [CommentaireController::class, 'destroy'])->middleware('api') ;

Route::get('/users/{id}', [UserController::class, 'show'])->middleware('api') ->where('id', '[0-9]+');
Route::put('/users/{id}', [UserController::class, 'update'])->middleware('api')->where('id', '[0-9]+') ;


