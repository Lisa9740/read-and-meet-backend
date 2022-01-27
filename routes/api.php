<?php

use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\ContactRequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\UserAuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\BookController;
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


Route::post('/register', [UserAuthController::class, 'register']) ;
Route::post('/connexion', [UserAuthController::class, 'login'])->name('login');
Route::get('/authorization', [UserAuthController::class, 'verifyToken'])->name('authorization');
Route::get('/logout', [UserAuthController::class, 'logout'])->middleware('auth:api')->name('api.logout');

Route::middleware(['auth:api'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('api.users');
    Route::get('/posts', [PostController::class, 'index'])->name('api.posts');
    Route::get('/books', [BookController::class, 'index'])->name('api.books');
    Route::get('/profiles', [ProfileController::class, 'index'])->name('api.profiles');
});

Route::middleware(['auth:api'])->prefix('user')->group(function () {
    Route::get('/{id}', [UserController::class, 'show'])->where('id', "[0-9]+");
    Route::post('/update', [UserController::class, 'updateUser'])->name('api.users.update');
    Route::post('/update/avatar', [UserController::class, 'updateAvatar']);
    Route::post('/update/password', [UserController::class, 'updatePassword']);
});



Route::middleware(['auth:api'])->prefix('post')->group(function () {
    Route::get('/{id}', [PostController::class, 'show'])->where('id', "[0-9]+");
    Route::post('/create', [PostController::class, 'store'])->name('api.post.create');
    Route::post('/update/{id}', [PostController::class, 'update'])->name('api.post.update');
    Route::post('/delete/{id}', [PostController::class, 'destroy'])->name('api.post.delete');
});


Route::middleware(['auth:api'])->prefix('book')->group(function () {
    Route::get('/{id}', [BookController::class, 'show'])->where('id', "[0-9]+");
    Route::post('/create', [BookController::class, 'store'])->name('api.book.create');
});


Route::middleware(['auth:api'])->prefix('profile')->group(function () {
    Route::get('/{id}', [ProfileController::class, 'show'])->where('id', "[0-9]+");
    Route::post('/edit/{id}', [ProfileController::class, 'edit'])->where('id', "[0-9]+");
    Route::post('/edit/visibility/{id}', [ProfileController::class, 'changeVisibility'])->where('id', "[0-9]+");
    Route::post('/edit/photo/{id}', [ProfileController::class, 'changePhoto'])->where('id', "[0-9]+");
});


Route::middleware(['auth:api'])->prefix('contact')->group(function () {
    Route::post('/request/create', [ ContactRequestController::class, 'createRequest']);
    Route::get('/request/received', [ ContactRequestController::class, 'getReceivedContactRequest'])->where('id', "[0-9]+");
    Route::get('/request/sent', [ ContactRequestController::class, 'getSentContactRequest']);
    Route::post('/request/accept/{id}',  [ ContactRequestController::class, 'acceptContactRequest'])->where('id', "[0-9]+");
    Route::post('/request/remove/{id}',  [ ContactRequestController::class, 'removeContactRequest'])->where('id', "[0-9]+");
    Route::get('/list',  [ ContactController::class, 'index']);

});

Route::middleware(['auth:api'])->prefix('chat')->group(function () {
    Route::post('/create', [ ChatController::class, 'create']);
    Route::post('/message/create', [ ChatController::class, 'createMessage']);
    Route::get('/{id}/messages', [ ChatController::class, 'showMessagesByChat']);
    Route::post('/{id}', [ ChatController::class, 'get']);
    Route::get('/list',  [ ChatController::class, 'showByUser']);
});


//Route::middleware('auth:api')->group( function () {
//    Route::resource('posts', BookPostController::class);
//});
