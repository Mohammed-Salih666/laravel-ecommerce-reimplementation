<?php

namespace App\Http\Controllers;

use App\Models\GlobalInventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return GlobalInventory::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        GlobalInventory::create($request->validate([
            'quantity' => 'required|min:0'
        ]));

        return response()->json([
            'message' => 'New inventory entry has been inserted.',
            'data' => $request
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return GlobalInventory::findOrFail($id); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        GlobalInventory::findOrFail($id)->update($request->validate([
            'quantity' => 'required|min:0'
        ])); 

        return response()->json([
            'message' => 'inventory entry has been updated', 
            'data' => $request
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        GlobalInventory::destroy($id); 

        return response()->json([
            'message' => 'entry has been deleted'
        ]);
    }
}
