<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JawabanController;
use App\Http\Controllers\PertanyaanController;
use App\Http\Controllers\UserController;
use App\Models\Pertanyaan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::prefix('dashboard')
    ->middleware(['auth:sanctum', 'admin'])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::resource('questions', PertanyaanController::class);
        Route::resource('answers', JawabanController::class);
        Route::get('/search/user', [UserController::class, 'searchUser'])->name('search_user');
        Route::get('/search/question', [PertanyaanController::class, 'searchQuestion'])->name('search_question');

        Route::post('/verified/{id}', [JawabanController::class, 'verified'])->name('verified');
        Route::post('/not-verified/{id}', [JawabanController::class, 'notVerified'])->name('notVerified');
    });
