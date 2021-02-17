<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeloveController;

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

Route::get('/', [WeloveController::class, 'index'])->name('index');
Route::get('/edit', [WeloveController::class, 'create']);
Route::post('/edit', [WeloveController::class, 'insert']);
Route::get('/edit/{ID}', [WeloveController::class, 'edit']);
Route::post('/edit/{ID}', [WeloveController::class, 'modify']);
Route::post('/delete/{ID}', [WeloveController::class, 'delete']);
