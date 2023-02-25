<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

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

Route::prefix('user')->middleware(['apikeyverification'])->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::get('/list', [UserController::class, 'list']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::patch('/', [UserController::class, 'patch']);
});
