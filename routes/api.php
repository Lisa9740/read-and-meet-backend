<?php

use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\ContactRequestController;
use App\Http\Controllers\API\DeviceTokenController;
use App\Http\Controllers\API\ImageController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\ProductController;
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

/***
 *
 * AUTH ROUTES
 *
 */

Route::post('/register', [UserAuthController::class, 'register']) ;
Route::post('/connexion', [UserAuthController::class, 'login'])->name('login');
Route::get('/authorization', [UserAuthController::class, 'verifyToken'])->name('authorization');
Route::get('/logout', [UserAuthController::class, 'logout'])->middleware('auth:api')->name('api.logout');

Route::get('/products', [ProductController::class, 'index']);

/***
 *
 * USERS ROUTES
 *
 */

Route::middleware(['auth:api'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('api.users');
});
Route::middleware(['auth:api'])->prefix('user')->group(function () {
    Route::get('/{id}', [UserController::class, 'show'])->where('id', "[0-9]+");
    Route::post('/update', [UserController::class, 'updateUser'])->name('api.users.update');
    Route::post('/update/avatar', [UserController::class, 'updateAvatar']);
    Route::post('/update/password', [UserController::class, 'updatePassword']);

    Route::put('profile/visibility', [ProfileController::class, 'changeVisibility']);

    Route::get('/contacts', [ContactController::class, 'show'])->where('id', "[0-9]+");
    Route::get('/posts', [PostController::class, 'showPosts'])->where('id', "[0-9]+");
    Route::get('/chats',  [ ChatController::class, 'showByUser']);
    Route::get('/messages',  [ MessageController::class, 'showByUser']);
    Route::get('/chat/{id}', [ MessageController::class, 'showMessagesByChat'])->where('id', "[0-9]+");
    Route::get('/notifications', [NotificationController::class, 'show']);
});

/***
 *
 * POSTS ROUTES
 *
 */

Route::get('/posts', [PostController::class, 'index'])->name('api.posts');

Route::get('/post/image/{url}', [ImageController::class, 'getPostImage'])->name('api.post.image');

Route::middleware(['auth:api'])->prefix('post')->group(function () {
    Route::post('/', [PostController::class, 'create'])->name('api.post.create');
    Route::get('/{id}', [PostController::class, 'show'])->where('id', "[0-9]+");
    Route::put('/{id}', [PostController::class, 'update'])->name('api.post.update');
    Route::delete('/delete/{id}', [PostController::class, 'destroy'])->name('api.post.delete');
});


/***
 *
 * BOOKS ROUTES
 *
 */

Route::get('/books', [BookController::class, 'index'])->name('api.books');

Route::get('/book/post/{id}', [BookController::class, 'showByPost'])->name('api.get.book.posts');
Route::get('/{id}', [BookController::class, 'show'])->where('id', "[0-9]+");

Route::middleware(['auth:api'])->prefix('book')->group(function () {
    Route::post('/', [BookController::class, 'store'])->name('api.book.store');
  });

/***
 *
 * PROFILES ROUTES
 *
 */
Route::get('/image/{url}', [ImageController::class, 'getPostImage'])->name('api.post.image');
Route::middleware(['auth:api'])->group(function () {
    Route::get('/profiles', [ProfileController::class, 'index'])->name('api.profiles');
});

Route::middleware(['auth:api'])->prefix('profile')->group(function () {
    Route::get('/{id}', [ProfileController::class, 'show'])->where('id', "[0-9]+");
    Route::put('/{id}', [ProfileController::class, 'edit'])->where('id', "[0-9]+"); // TODO : change to method post to put
    Route::put('/visibility/{id}', [ProfileController::class, 'changeVisibility'])->where('id', "[0-9]+"); // TODO : change to method post to put
    Route::put('/photo/{id}', [ProfileController::class, 'changePhoto'])->where('id', "[0-9]+"); // TODO : change to method post to put
});

/***
 *
 * CONTACTS ROUTES
 *
 */
Route::middleware(['auth:api'])->group(function () {
    Route::get('/contacts',  [ ContactController::class, 'index']);
});

/***
 *
 * CONTACT REQUESTS ROUTES
 *
 */

Route::get('/contact-requests', [ ContactRequestController::class, 'index']);

Route::middleware(['auth:api'])->prefix('contact-request')->group(function () {
    Route::post('/', [ ContactRequestController::class, 'store']);
    Route::get('/received', [ ContactRequestController::class, 'getReceived']);
    Route::get('/sent', [ ContactRequestController::class, 'getSentContactRequest']);
    Route::post('/accept/{id}',  [ ContactRequestController::class, 'acceptContactRequest'])->where('id', "[0-9]+");
    Route::post('/remove/{id}',  [ ContactRequestController::class, 'removeContactRequest'])->where('id', "[0-9]+");

});

/***
 *
 * CHATS ROUTES
 *
 */
Route::middleware(['auth:api'])->prefix('chat')->group(function () {
    Route::post('/', [ ChatController::class, 'store']);
    Route::post('/{id}', [ ChatController::class, 'get']);
});

/***
 *
 * MESSAGES ROUTES
 *
 */
Route::get('/messages', [ MessageController::class, 'index']);

Route::middleware(['auth:api'])->prefix('message')->group(function () {
    Route::post('/', [ MessageController::class, 'store']);
});

/***
 *
 * NOTIFICATIONS ROUTES
 *
 */

Route::middleware(['auth:api'])->prefix('notification')->group(function () {
    Route::post('/', [ NotificationController::class, 'store'])->name('api.notification.store');
});

/***
 *
 * MEDIAS ROUTES
 *
 */

Route::middleware(['auth:api'])->prefix('media')->group(function () {
    Route::post('/upload/image', [ImageController::class, 'savePostImages'])->name('api.post.image.upload');
});


/***
 *
 * DEVICES ROUTES
 *
 */

Route::middleware(['auth:api'])->group(function () {
    Route::post('/device', [DeviceTokenController::class, 'store'])->name('api.store.devices');
    Route::get('/devices', [DeviceTokenController::class, 'index'])->name('api.get.devices');
});

