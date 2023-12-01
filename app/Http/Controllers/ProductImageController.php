<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        $images = $product->images(); 
        return response()->json([
            'product' => $product->id, 
            'images' => $images
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $image = ProductImage::create($request->validated); 

        return response()->json([
            'image' => $image
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductImage $image)
    {
        $image = ProductImage::findOrFail($image->id); 

        return response()->json([
            'image' => $image
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductImage $image)
    {
        $old = $image; 
        ProductImage::where('id', $image->id)->update($request->validated());

        return response()->json([
            'old_image' => $old, 
            'new_image' => $image
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductImage $image)
    {
        ProductImage::destroy($image->id); 

        return response()->json([
            'image' => $image,
            'status' => 'deleted'
        ]);
    }
}
