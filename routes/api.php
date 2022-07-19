<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostImageController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

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

Route::group([
    'controller' => AuthController::class
], function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::middleware('auth:sanctum')->post('/logout', 'logout');
});

Route::middleware('auth:sanctum')
    ->apiResource('post', PostController::class)
    ->except(['index', 'show']);
Route::apiResource('post', PostController::class)
    ->only(['index', 'show']);

Route::group([
    'controller' => CommentController::class,
    'prefix' => 'comment'
], function() {
    Route::get('/{id_post}', 'index');
    Route::get('/', 'userComments')->middleware('auth:sanctum');
    Route::post('/', 'store');
});
Route::middleware('auth:sanctum')
    ->apiResource('comment', CommentController::class)
    ->only('update', 'destroy');

Route::get('/img/{file}', [PostImageController::class, 'show'])
    ->where(['file' => '.*'])
    ->name('post.image');

Route::group([
    'controller' => SubscriberController::class,
    'prefix' => 'subscriber'
], function() {
    Route::get('/{id}/{token}', 'checkIfSubscriberIsCorrect');
    Route::delete('/{id}/{token}', 'destroy');
});
Route::apiResource('subscriber', SubscriberController::class)->only('index', 'store');
