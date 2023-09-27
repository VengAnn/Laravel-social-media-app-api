<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Models\Comment;
use  App\Http\Controllers\LikeController;
use App\Models\Like;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::post('/update-profile', [AuthController::class, 'updateUser'])->middleware('auth:api');

//Post routes
Route::get('/posts', [PostController::class, 'index']);
// Route::get('/posts/{id}', [PostController::class, 'show']);
Route::post('/posts', [PostController::class, 'store'])->middleware('auth:api');
Route::post('/posts/{id}', [PostController::class, 'update'])->middleware('auth:api');
Route::delete('/posts/{id}', [PostController::class, 'destroy'])->middleware('auth:api');


//Comment Routes
Route::get('/posts/{postId}/comments', [CommentController::class, 'show'])->middleware('auth:api');
Route::post('/posts/{postId}/comments', [CommentController::class, 'store'])->middleware('auth:api');
Route::post('/posts/comments/{id}', [CommentController::class, 'update'])->middleware('auth:api');
Route::delete('/posts/comments/{id}', [CommentController::class, 'destroy'])->middleware('auth:api');


//Like Routes
Route::get('/posts/{postId}/likes', [LikeController::class, 'show'])->middleware('auth:api'); //middleware use for user in login with token => ok can show get delete post meaning of middleware
Route::post('/posts/{postId}/likes', [LikeController::class, 'togglelike'])->middleware('auth:api');
