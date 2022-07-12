<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostImageController;
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

Route::get('/img/{file}', [PostImageController::class, 'show'])
    ->where(['file' => '.*'])
    ->name('post.image');
