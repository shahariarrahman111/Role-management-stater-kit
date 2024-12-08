<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/register', [UserController::class, 'RegistrationForm'])->name('register.form');
Route::post('/register', [UserController::class, 'UserRegister'])->name('user.register');
Route::get('/login', [UserController::class, 'LoginForm'])->name('login.form');
Route::post('/login', [UserController::class, 'userLogin'])->name('user.login');
Route::get('/home',[UserController::class, 'HomePage'])->name('user.home');

Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'Dashboard'])->name('admin.dashboard');
});
