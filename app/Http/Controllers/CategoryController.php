<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Category::all(); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('create.category.form'); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Category::create($request); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return Category::findOrFail($category->id); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('edit.category.form');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        Category::where('id', $category->id)->update($request->validated); 
        return Redirect::to('/categories'); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        Category::destroy($category->id); 
        Redirect::to('/categories'); 

        return response($category);
    }

    public function getProducts(Category $category) {
        return $category->products();
    }
}
