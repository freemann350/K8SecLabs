<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DefinitionController;
use App\Http\Controllers\EnvironmentAccessController;
use App\Http\Controllers\EnvironmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
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

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/Dashboard','index')->name("Dashboard");
    });
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

    Route::controller(CategoryController::class)->group(function () {
        Route::resource('/Categories',CategoryController::class);
    })->middleware(AdminMiddleware::class);
    
    Route::controller(DefinitionController::class)->group(function () {
        Route::resource('/Definitions',DefinitionController::class);
        Route::get('/Definitions/addDefinition/{id}','addDefinition')->name("Definitions.addDefinition");
        Route::delete('/Definitions/removeDefinition/{id}','removeDefinition')->name("Definitions.removeDefinition");
        Route::put('/DefinitionsFile/{Definition}','updateDefinition')->name("Definitions.updateDefinition");
        Route::get('/DefinitionsCatalog','catalog')->name("Definitions.catalog");
        Route::get('/DownloadDefinition/{id}','download')->name("Definitions.download");
    })->middleware(AdminMiddleware::class);

    Route::controller(EnvironmentController::class)->group(function () {
        Route::resource('/Environments',EnvironmentController::class)->except('edit','update');
    })->middleware(AdminMiddleware::class);

    Route::controller(EnvironmentAccessController::class)->group(function () {
        Route::resource('/EnvironmentAccesses',EnvironmentAccessController::class)->only('index','show');
        Route::get('/Join/{id}','code')->name("Join");
        Route::get('/JoinEnvironment/{id}','code')->name("EnvironmentAccesses.code");
        Route::post('/JoinEnvironment/{id}','join')->name("EnvironmentAccesses.join");
    });
});