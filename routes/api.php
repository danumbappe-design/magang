<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PhotoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Illuminate\Routing\RouteGroup;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['controller' => AuthController::class], function () {
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
    Route::get('/show', 'show')->middleware('auth:sanctum');
});

Route::middleware(['auth:sanctum'])->controller(ProductController::class)->group(function () {
    Route::get('/products', 'index');
    Route::get('/product/{id}', 'show');
    Route::post('/products', 'store');
    Route::put('/product/{id}', 'update');
    Route::delete('/product/{id}', 'destroy');
});


Route::middleware('auth:sanctum')->controller(PhotoController::class)->group(function () {
    Route::post('/product/{id}/photo', 'store');
    // Route::put('/product/{product_id}/photo/update', 'update');
    Route::delete('/product/{product_id}/photo/delete',  'destroy');
});

Route::middleware('auth:sanctum')->controller(CommentController::class)->group(function () {
    Route::post('/product/{product_id}/comment', 'store');
    Route::delete('/product/{product_id}/comment/{id}/delete',  'destroy');
});

Route::middleware('auth:sanctum')->controller(CartController::class)->group(function () {
    Route::get('/carts', 'index');
    Route::post('/product/{product_id}/carts', 'store');
    Route::put('/carts/update', 'update');
    Route::delete('/carts/{id}',  'destroy');
});
 