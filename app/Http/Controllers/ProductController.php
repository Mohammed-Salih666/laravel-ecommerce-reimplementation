<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Redirect;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('create.product.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $product = Product::create($request->validated());

        // $product = new Product(); 
        // $data = $this->prepare($request->validated(), $product->getFillable()); 
        // $product->fill($data); 
        // $product->save(); 
        
        return Redirect::to('/products'); 
        //return response()->json(['product' => $product, 'message' => 'product added']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return Product::findOrFail($product->id); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('edit.product.form'); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        Product::where('id', $product->id)->update($request->validated()); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        Product::destroy($product->id); 
        Redirect::to('/products'); 
        return response()->json([
            'product' => $product, 
            'status' => 'deleted'
        ]);
    }
}
