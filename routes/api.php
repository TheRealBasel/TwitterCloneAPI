<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FollowingController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostLikeController;


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

Route::middleware('auth:sanctum')->group( 
    function () {
        Route::resource ('user', UserController::class);

        /* Following Routes */
        Route::post('follow', [FollowingController::class, 'follow']);
        Route::delete('follow', [FollowingController::class, 'unfollow']);
        Route::get('followers/{id}', [FollowingController::class, 'followers']);
        Route::get('following/{id}', [FollowingController::class, 'followings']);

        /* Posts Routes */
        Route::resource ('post', PostController::class);
        Route::get('likes/{id}', [PostLikeController::class, 'show']);
        Route::post('like', [PostLikeController::class, 'store']);
        Route::delete('like', [PostLikeController::class, 'destroy']);

    }
);

Route::prefix('/auth')->group(
    function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::delete('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::get('username', [AuthController::class, 'randomizeUserName']);
    }
);