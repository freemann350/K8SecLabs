<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DefinitionController;
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

    /* Route::controller(CategoryController::class)->group(function () {
        Route::get('/Category/me', 'editMe')->name("Category.editMe")
        ->middleware('can:updateMe,App\Models\Category');
        Route::put('/Category/{category}/me', 'updateMe')->name("Category.updateMe")
        ->middleware('can:updateMe,App\Models\Category');
        Route::patch('/Category/{category}/description', 'updateDescription')->name("Category.updateDescription")->middleware('can:updateMe,App\Models\Category');
    }); */
    
    Route::controller(UserController::class)->group(function () {
        Route::get('/Users','index')->name("Users.index")->middleware('can:view,App\Models\User');
        Route::get('/Users/create','create')->name("Users.create")->middleware('can:create,App\Models\User');
        Route::post('/Users','store')->name("Users.store")->middleware('can:store,App\Models\User');
        Route::get('/Users/{user}/edit','edit')->name("Users.edit")->middleware('can:edit,App\Models\User');
        Route::put('/Users/{user}','update')->name("Users.update")->middleware('can:update,App\Models\User');
        Route::delete('/Users/{user}','destroy')->name("Users.destroy")->middleware('can:delete,App\Models\User');
    })->middleware(AdminMiddleware::class);

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/Categories', 'index')->name("Categories.index");
        Route::get('/Categories/create', 'create')->name("Categories.create");
        Route::post('/Categories', 'store')->name("Categories.store");
        Route::get('/Categories/{category}/edit', 'edit')->name("Categories.edit");
        Route::put('/Categories/{category_id}', 'update')->name("Categories.update");
        Route::delete('/Categories/{category}', 'destroy')->name("Categories.destroy");
    })->middleware(AdminMiddleware::class);
    
    Route::controller(DefinitionController::class)->group(function () {
        Route::get('/definitions', [DefinitionController::class, 'index'])->name('Definitions.index');
        Route::get('/definitions/create', [DefinitionController::class, 'create'])->name('Definitions.create');
        Route::post('/definitions', [DefinitionController::class, 'store'])->name('Definitions.store');
        Route::get('/definitions/{definition}', [DefinitionController::class, 'show'])->name('Definitions.show');
        Route::get('/definitions/{definition}/edit', [DefinitionController::class, 'edit'])->name('Definitions.edit');
        Route::put('/definitions/{definition}', [DefinitionController::class, 'update'])->name('Definitions.update');
        Route::delete('/definitions/{definition}', [DefinitionController::class, 'destroy'])->name('Definitions.destroy');
    })->middleware(AdminMiddleware::class);
});