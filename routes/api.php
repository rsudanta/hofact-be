<?php

use App\Http\Controllers\API\JawabanController;
use App\Http\Controllers\API\PertanyaanController;
use App\Http\Controllers\API\UserController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('user', [UserController::class, 'updateProfile']);
    Route::post('user/photo', [UserController::class, 'updatePhoto']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('user/password', [UserController::class, 'updatePassword']);
    Route::post('user/point/{addPoint}', [UserController::class, 'getPoint']);
    Route::post('pertanyaan', [PertanyaanController::class, 'create']);
    Route::post('pertanyaan/photo/{id}', [PertanyaanController::class, 'updatePhoto']);

    Route::post('jawaban', [JawabanController::class, 'create']);
    Route::post('upvote/{id}', [JawabanController::class, 'upvote']);
    Route::post('downvote/{id}', [JawabanController::class, 'downvote']);
});


Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);

Route::get('pertanyaan', [PertanyaanController::class, 'all']);

Route::get('jawaban', [JawabanController::class, 'all']);
