<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DefinitionController;
use App\Http\Controllers\EnvironmentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::get('/','index')->name("login");
    Route::post('/Login','login')->name("Auth.login");
});

Route::middleware('auth')->group(function () {
    Route::get('/Dashboard', function () {
        return view('dashboard.index');
    })->name('Dashboard');

    Route::controller(AuthController::class)->group(function () {
        Route::get('/Logout','logout')->name("Auth.logout");
    });
});
/*
Route::get('/', function () {
    return view('welcome');
});
*/