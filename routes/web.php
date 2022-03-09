<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

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

Route::fallback(function(){
    return view('errors.404');
});

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();


Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['guest:admin'])->group(function () {
        Route::view('/login', 'admin.login')->name('login');
        Route::view('/register', 'admin.register')->name('register');
        Route::post('/create',[AdminController::class, 'create'])->name('create');
        Route::post('/check', [AdminController::class, 'check'])->name('check');
    });
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/home', [AdminController::class, 'home'])->name('home');
        Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
    });
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::view('/', 'welcome')->name('welcome');
