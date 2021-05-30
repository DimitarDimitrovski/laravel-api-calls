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

Route::get('kitties', [KittyApiController::class, 'index'])->name('kitties');
Route::post('kitties/{id}/add-favourite', [KittyApiController::class, 'addFavourite'])->name('kitties.image.favourite.add');
Route::post('kitties/{id}/vote', [KittyApiController::class, 'addVote'])->name('kitties.image.vote');
Route::get('kitties/breeds', [KittyApiController::class, 'breeds'])->name('kitties.breeds');
Route::get('kitties/user/images', [KittyApiController::class, 'userImages'])->name('kitties.user.images');
Route::post('kitties/user/images/upload', [KittyApiController::class, 'upload'])->name('kitties.user.images.upload');
Route::delete('kitties/user/images/{id}', [KittyApiController::class, 'delete'])->name('kitties.user.images.delete');
Route::get('kitties/user/favourites', [KittyApiController::class, 'favourites'])->name('kitties.user.favourites');
Route::get('kitties/user/votes', [KittyApiController::class, 'votes'])->name('kitties.user.votes');
