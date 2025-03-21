<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    // Comments API end points
    Route::post('/posts/comments', [CommentController::class, 'store']);
    Route::get('/posts/{postId}/comments', [CommentController::class, 'index']);
    Route::get('/comments/{id}', [CommentController::class, 'show']);
    Route::post('/comments/{commentId}/reply', [CommentController::class, 'reply']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
    Route::get('/comments', [CommentController::class, 'index1']);

    // Like API end points
    Route::post('/posts/like', [LikeController::class, 'store']);
    Route::get('/posts/{postId}/likes', [LikeController::class, 'index']);

    // Share API end points
    Route::post('/posts/{postId}/share', [ShareController::class, 'store']);
    Route::get('/posts/{postId}/shares', [ShareController::class, 'index']);
    Route::get('/my-shares', [ShareController::class, 'myShares']);
    Route::delete('/shares/{id}', [ShareController::class, 'destroy']);

    // Posts API endpoint s
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts/{id}', [PostController::class, 'show']);
    Route::put('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);
    Route::get('/user_posts/{id}', [PostController::class, 'postUser']);

    // Auth and profile API end points
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get("/users",[AuthController::class,"index"]);
});
