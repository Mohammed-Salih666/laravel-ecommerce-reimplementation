<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('/users', UserController::class); 

Route::resource('/users/{user}/addresses', AddressController::class);

Route::get('/users/{user}/cart', [CartController::class, 'index']);
Route::post('/users/{user}/cart/add-product', [CartController::class, 'add']);
Route::delete('/users/{user}/cart', [CartController::class, 'remove']);

Route::resource('/products', ProductController::class); 

Route::resource('/categories', CategoryController::class);
Route::get('/categories/{category}/products', [CategoryController::class, 'getProducts']);