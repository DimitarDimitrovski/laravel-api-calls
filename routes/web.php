<?php

use App\Http\Controllers\KittyApiController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::prefix('kitties')->name('kitties')->group(function() {
    Route::get('', [KittyApiController::class, 'index']);
    Route::post('{id}/add-favourite', [KittyApiController::class, 'addFavourite'])->name('.image.favourite.add');
    Route::post('{id}/vote', [KittyApiController::class, 'addVote'])->name('.image.vote');
    Route::get('breeds', [KittyApiController::class, 'breeds'])->name('.breeds');

    Route::prefix('user')->name('.user')->group(function() {
        Route::get('favourites', [KittyApiController::class, 'favourites'])->name('.favourites');
        Route::get('votes', [KittyApiController::class, 'votes'])->name('.votes');

        Route::prefix('images')->name('.images')->group(function() {
            Route::get('', [KittyApiController::class, 'userImages']);
            Route::post('upload', [KittyApiController::class, 'upload'])->name('.upload');
            Route::delete('{id}', [KittyApiController::class, 'delete'])->name('.delete');
        });
    });
});

