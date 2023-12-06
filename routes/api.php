<?php

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Public: 
Route::get('/home', [HomeController::class, 'index']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::resource('/products', ProductController::class)->only(['index', 'show']); 

Route::resource('/categories', CategoryController::class)->only(['index', 'show']);
Route::get('/categories/{category}/products', [CategoryController::class, 'getProducts']);

//Private: 
Route::middleware('auth:sanctum')->group(function(){

    Route::resource('/users/{user}/addresses', AddressController::class);

    Route::get('/users/{user}/cart', [CartController::class, 'index']);
    Route::post('/users/{user}/cart/add-product', [CartController::class, 'add']);
    Route::delete('/users/{user}/cart', [CartController::class, 'remove']);


    Route::post('/logout', [AuthController::class, 'logout']);
});

//Admin Operations
Route::middleware('auth:sanctum', 'admin')->group(function() {
    Route::post('/authorize', [AuthController::class, 'authorizeTo']);
    Route::post('/unauthorize', [AuthController::class, 'unauthorize']);
    Route::resource('/users', UserController::class); 
    Route::resource('/products', ProductController::class)->except(['index', 'show']);
    Route::resource('/categories', CategoryController::class)->except(['index', 'show']);
});
