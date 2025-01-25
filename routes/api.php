<?php

use App\Http\Controllers\Date\DateController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\MarriageController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\TreeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum', 'ability:admin'])->group(function () {
    Route::post('/photo/temp', [PhotoController::class, 'storeTemp'])->name('photo.temp.store');
    Route::resource('person', PersonController::class)->except(['index', 'show']);
    Route::get('/genders', [GenderController::class, 'getAll'])->name('gender.all');
    Route::prefix('/marriage/')->name('marriage.')->group(function () {
        Route::get('roles', [MarriageController::class, 'getRoleAll'])->name('roles.all');
        Route::get('roles/{gender}', [MarriageController::class, 'getRoleByGender'])->name('roles.gender');
        Route::get('possible', [MarriageController::class, 'getPossible'])->name('possible');
    });
    Route::prefix('/parent/')->name('parent.')->group(function () {
        Route::get('roles', [ParentController::class, 'getRoleAll'])->name('roles.all');
        Route::get('possible', [ParentController::class, 'getPossible'])->name('possible');
    });
});

Route::middleware(['auth:sanctum', 'ability:user'])->group(function () {
    Route::get('/person/{id}', [PersonController::class, 'show'])->name('person.show');
    Route::get('/person-photo/{personId}', [PhotoController::class, 'getListByPerson'])->name('photo.list');
    Route::get('/photo/{personId}/{fileName}', [PhotoController::class, 'show'])->name('photo.show');
    Route::get('/people', [PeopleController::class, 'getAll'])->name('people.all');
    Route::prefix('/tree/')->name('tree.')->group(function () {
        Route::get('model/{id}', [TreeController::class, 'getModel'])->name('model');
        Route::get('image/{id}', [TreeController::class, 'getImage'])->name('image');
        Route::get('image-interactive/{id}', [TreeController::class, 'getImageInteractive'])
            ->name('image_interactive');
        Route::get('toggle/{id}', [TreeController::class, 'getToggle'])->name('toggle');
    });
    Route::prefix('/dates/')->name('dates.')->group(function () {
        Route::get('', [DateController::class, 'getAll'])->name('all');
        Route::get('upcoming', [DateController::class, 'getUpcoming'])->name('upcoming');
    });
    Route::prefix('/download/')->name('download.')->group(function () {
        Route::get('people', [DownloadController::class, 'getPeople'])->name('people');
        Route::get('person/{id}', [DownloadController::class, 'getPerson'])->name('person');
        Route::get('tree/{id}', [DownloadController::class, 'getTree'])->name('tree');
        Route::get('db', [DownloadController::class, 'getDataBase'])->name('data_base');
        Route::get('photo', [DownloadController::class, 'getPhoto'])->name('photo');
    });
});
