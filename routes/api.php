<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Admin-only routes here
    Route::get('/admin/users', [AdminController::class, 'indexUsers']);
    Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser']);
    // Add other admin routes
});

//$make another table for admins and make auth for it and genertae guard and use it
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return Auth::user();
    });

    // Route::apiResource('users',controller: UserController::class);
    Route::prefix('users')->group(function () {
        Route::get('/{user_id}', [UserController::class, 'show']);
        Route::patch('/{user_id}', [UserController::class, 'update']);
        Route::delete('/', [UserController::class, 'destroy']);
        Route::get('/{user_id}/favourites', [FavouriteController::class, 'index']);
        Route::post('/{user_id}/favourites', [FavouriteController::class, 'store']);
        Route::delete('/{user_id}/favourites/{favourite_id}', [FavouriteController::class, 'destroy']);
    });

    // Product
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{product_id}', [ProductController::class, 'show']);
        Route::post('/', [ProductController::class, 'store']);
        Route::patch('/{product_id}', [ProductController::class, 'update']);
        Route::delete('/{product_id}', [ProductController::class, 'destroy']);

        Route::post('/{product_id}/images', [ProductImageController::class, 'store']);
        Route::delete('/{product_id}/images/{image_id}', [ProductImageController::class, 'destroy']);

        Route::post('/{product_id}/reviews', [ReviewController::class, 'store']);
        Route::delete('/{product_id}/reviews/{review_id}', [ReviewController::class, 'destroy']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{order_id}', [OrderController::class, 'show']);
        Route::post('/', [OrderController::class, 'store']);
        Route::delete('/{order_id}', [OrderController::class, 'destroy']);
        Route::patch('/{order_id}', [OrderController::class, 'update']);
    });

    Route::prefix('carts')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/', [CartController::class, 'store']);
        Route::patch('/{product_id}', [CartController::class, 'update']);
        Route::delete('/{product_id}', [CartController::class, 'destroy']);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});
