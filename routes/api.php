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
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WholesalerController;
use App\Models\Warehouse;

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

    Route::post('/checkout', [CheckoutController::class, 'checkout']); 
    Route::get('/success', [CheckoutController::class, 'checkout'])->name('checkout.success'); 
    Route::get('/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');    

    Route::post('/logout', [AuthController::class, 'logout']);
});

//Admin Operations
Route::middleware('auth:sanctum', 'admin')->group(function() {
    Route::post('/authorize', [AuthController::class, 'authorizeTo']);
    Route::post('/unauthorize', [AuthController::class, 'unauthorize']);
    Route::resource('/users', UserController::class); 
    Route::resource('/products', ProductController::class)->except(['index', 'show']);
    Route::resource('/categories', CategoryController::class)->except(['index', 'show']);
    Route::resource('/inventory', InventoryController::class);
    Route::resource('/warehouses', WarehouseController::class);
});

Route::middleware('auth:sanctum', 'wholesaler')->group(function(){
    Route::post('/wholesaler/{wholesaler}/warehouse/{warehouse}/add', [WarehouseController::class, 'addToWarehouse']); 
    Route::post('/wholesaler/{wholesaler}/warehouse/{warehouse}/remove', [WarehouseController::class, 'removeFromWarehouse']); 
    Route::post('/wholesaler/{wholesaler}/warehouse/{warehouse}/insert', [WarehouseController::class, 'insertNewProduct']); 
    Route::delete('/wholesaler/{wholesaler}/warehouse/{warehouse}/delete-product', [WarehouseController::class, 'deleteWarehouseProduct']);

    Route::resource('/wholesaler', WholesalerController::class); 
    Route::resource('/wholesaler/{wholesaler}/warehouses', WarehouseController::class)->except(['index']); 

    Route::get('/wholesaler/{wholesaler}/warehouses/{warehouse}', [WarehouseController::class, 'show']);
    Route::get('/wholesaler/{wholesaler}/warehouses', [WholesalerController::class, 'getWarehouses']);
});