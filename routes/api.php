<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ResetPassController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\CommentController;

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/forgot', [ResetPassController::class, 'forgot']);
    Route::post('/reset', [ResetPassController::class, 'reset']);
    Route::get('/posts', [PostController::class,'index']);
});
Route::middleware('auth:sanctum', 'verified')->group(function () {
    Route::get('/authenticated', [AuthController::class, 'authenticated']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::apiResource('/comments', CommentController::class);
    Route::apiResource('/posts', PostController::class)->except('index');
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/email/check', [EmailVerificationController::class, 'check']);
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
});
