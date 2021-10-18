<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\UserAuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\BookPostController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('/register', [UserAuthController::class, 'register']) ;
Route::post('/connexion', [UserAuthController::class, 'login'])->name('login');
Route::get('/authorization', [UserAuthController::class, 'verifyToken'])->name('authorization');
Route::get('/logout', [UserAuthController::class, 'logout'])->middleware('auth:api')->name('api.logout');


Route::get('/users', [UserController::class, 'index'])->name('api.users');
Route::middleware(['auth:api'])->prefix('user')->group(function () {
    Route::get('/{id}', [UserController::class, 'show'])->where('id', "[0-9]+");
    Route::post('/update', [UserController::class, 'updateUser'])->name('api.users.update');
    Route::post('/update/avatar', [UserController::class, 'updateAvatar']);
    Route::post('/update/password', [UserController::class, 'updatePassword']);
});


Route::get('/posts', [BookPostController::class, 'index'])->name('api.posts');
Route::middleware(['auth:api'])->prefix('post')->group(function () {
    Route::get('/{id}', [BookPostController::class, 'show'])->where('id', "[0-9]+");
    Route::post('/create', [BookPostController::class, 'store'])->name('api.post.create');
    Route::post('/update/{id}', [BookPostController::class, 'update'])->name('api.post.update');
    Route::post('/delete/{id}', [BookPostController::class, 'destroy'])->name('api.post.delete');
});


Route::middleware('auth:api')->group( function () {
    Route::resource('posts', BookPostController::class);
});
