<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\GlobalInventory;
use App\Http\Requests\WarehouseRequest;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Warehouse::all(); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WarehouseRequest $request)
    {
        $warehouse = Warehouse::create($request->validated());
        return response()->json([
            'messsage' => 'Warehouse Created', 
            'data' => $warehouse
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        return Warehouse::findOrFail($warehouse->id); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WarehouseRequest $request, Warehouse $warehouse)
    {
        $warehouse = Warehouse::findOrFail($warehouse->id); 
        $warehouse->update($request->validated()); 

        return response()->json([
            'message' => 'warehouse updated',
            'data' => $warehouse
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        Warehouse::destroy($warehouse->id);  

        return response()->json([
            'message' => 'warehouse deleted',
            'data' => $warehouse
        ]);
    }



        
}
