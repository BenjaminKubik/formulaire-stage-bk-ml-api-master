<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChoiceController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FormController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Mail\PasswordMail;

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

Route::get('/email', function () {
    $val = (new App\Http\Controllers\AuthController)->generateRandomString();
    Mail::to('AureusLion@gmail.com')->send(new PasswordMail($val));
    return new PasswordMail($val);
});

Route::get('/authorizedUsers/{id}', [FormController::class, 'getAuthorizedUser'])->middleware('api'); //id = identifiant du formulaire
Route::get('/users', [UserController::class, 'userList'])->middleware('api');
Route::get('/roles', [RoleController::class, 'getRoleList'])->middleware('api');
Route::get('/getTextAnswers/{uId}/{fId}', [AnswerController::class, 'getFormsUsersTextAnswers'])->middleware('api');
Route::get('/getNumberAnswers/{uId}/{fId}', [AnswerController::class, 'getFormsUsersNumberAnswers'])->middleware('api');
Route::get('/getMultipleAnswers/{uId}/{fId}', [AnswerController::class, 'getFormsUsersMultipleAnswers'])->middleware('api');
Route::get('/demandes', [UserController::class, 'demandeList'])->middleware('api');
Route::post('/addDemande', [UserController::class, 'storeDemande'])->middleware('api');
Route::delete('/delDemande/{id}', [UserController::class, 'deleteDemande'])->middleware('api');
Route::post('/delFromForms', [UserController::class, 'delFromForms'])->middleware('api');
Route::get('/forms', [FormController::class, 'formList'])->middleware('api');
Route::get('/sections', [SectionController::class, 'sectionList'])->middleware('api');
Route::get('/questions', [QuestionController::class, 'questionList'])->middleware('api');
Route::get('/choix', [ChoiceController::class, 'choixList'])->middleware('api');
Route::get('/showFormByTitre/{titre}', [FormController::class, 'showByTitre'])->middleware('api');
Route::get('/showFormById/{id}', [FormController::class, 'showById'])->middleware('api');
Route::get('/showSection/{id}', [SectionController::class, 'show'])->middleware('api');
Route::get('/showQuestion/{id}', [QuestionController::class, 'show'])->middleware('api');
Route::get('/showChoix/{id}', [ChoiceController::class, 'show'])->middleware('api');
Route::post('/commentaires', [CommentaireController::class, 'store'])->middleware('api') ;
Route::post('/new-form', [FormController::class, 'store'])->middleware('api') ;
Route::post('/set-sec', [SectionController::class, 'store'])->middleware('api');
Route::post('/create-question', [QuestionController::class, 'store'])->middleware('api');
Route::post('/create-choix', [ChoiceController::class, 'store'])->middleware('api');
Route::post('/send-text', [AnswerController::class, 'storeText'])->middleware('api');
Route::post('/send-number', [AnswerController::class, 'storeNumber'])->middleware('api');
Route::post('/send-multiple', [AnswerController::class, 'storeMultiple'])->middleware('api');
Route::post('/addToForms', [UserController::class, 'addToForms'])->middleware('api');
Route::delete('/commentaires/{id}', [CommentaireController::class, 'destroy'])->middleware('api') ;
Route::get('/test/{uId}/{fId}', [AnswerController::class, 'getSomething'])->middleware('api') ->where('id', '[0-9]+');

Route::get('/answered/{id}', [AnswerController::class, 'getFormsAnswered'])->middleware('api') ->where('id', '[0-9]+');
Route::get('/users/{id}', [UserController::class, 'show'])->middleware('api') ->where('id', '[0-9]+');
Route::put('/users/{id}', [UserController::class, 'update'])->middleware('api')->where('id', '[0-9]+');
Route::put('/users/{email}', [UserController::class, 'reset'])->middleware('api') ;
Route::post('/deleteForms', [FormController::class, 'del'])->middleware('api') ;
Route::post('/del-multiple', [AnswerController::class, 'deleteMultipleAnswer'])->middleware('api') ;


