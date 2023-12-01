<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $products = Product::where('is_active', true)->get(); 
        $categories = Category::where('is_active', true)->get(); 

        return response()->json([
            'products' => $products, 
            'categories' => $categories
        ]);
    }
}
