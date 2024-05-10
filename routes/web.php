<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DefinitionController;
use App\Http\Controllers\EnvironmentController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::get('/','index')->name("login");
    Route::post('/Login','login')->name("Auth.login");
});

Route::get('/testing', function () {
    return view('welcome');
});

Route::controller(EnvironmentController::class)->group(function () {
    Route::post('/testing','createNamespace')->name("testing");

});

Route::middleware('auth')->group(function () {
    Route::get('/Dashboard', function () {
        return view('dashboard.index');
    })->name('Dashboard');

    Route::controller(AuthController::class)->group(function () {
        Route::get('/Logout','logout')->name("Auth.logout");
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/User/me','editMe')->name("User.editMe")
        ->middleware('can:updateMe,App\Models\User');
        Route::put('/User/{user}/me','updateMe')->name("User.updateMe")
        ->middleware('can:updateMe,App\Models\User');
        Route::patch('/User/{user}/password','updatePassword')->name("User.updatePassword")->middleware('can:updateMe,App\Models\User');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/Users','index')->name("Users.index")->middleware('can:view,App\Models\User');
        Route::get('/Users/create','create')->name("Users.create")->middleware('can:create,App\Models\User');
        Route::post('/Users','store')->name("Users.store")->middleware('can:store,App\Models\User');
        Route::get('/Users/{user}/edit','edit')->name("Users.edit")->middleware('can:edit,App\Models\User');
        Route::put('/Users/{user}','update')->name("Users.update")->middleware('can:update,App\Models\User');
        Route::delete('/Users/{user}','destroy')->name("Users.destroy")->middleware('can:delete,App\Models\User');
    })->middleware(AdminMiddleware::class);
});