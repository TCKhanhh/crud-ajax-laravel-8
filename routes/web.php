<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;


Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');





Route::middleware(['auth', 'checkRole'])->group(function () {
    Route::controller(UserController::class)->group(function () {

        Route::get('/listUser', [UserController::class, 'listUser'])->name('listUser');
        Route::get('/viewUser/{id}', [UserController::class, 'viewUser'])->name('viewUser');
        Route::get('/searchUsers', [UserController::class, 'searchUsers'])->name('searchUsers');
        Route::post('/storeUser', [UserController::class, 'storeUser'])->name('storeUser');
        Route::put('/updateUser/{id}', [UserController::class, 'updateUser'])->name('updateUser');
        Route::post('/deleteUser/{id}', [UserController::class, 'deleteUser'])->name('deleteUser');
    });
});