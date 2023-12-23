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

    //Add a quantity of products to a designated warehouse. 
    public function addToWarehouse(Request $request) 
    {
        $warehouse = Warehouse::where('id', $request->warehouse_id)->first(); 
        $product = $warehouse->products()->where('product_id', $request->product_id)->first(); 

        if($product == null) 
        {
            return response()->json([
                'message' => 'Product not found. Please insert the product to the warehouse first.'
            ], 404);
        }

        $warehouse->products()->updateExistingPivot($product->id, ['quantity' => $product->quantity + $request->quantity]);
        if(!$product->is_active)
        {
            $warehouse->products()->updateExistingPivot($product->id, ['is_active' => true]);
        }
        // $product->update([
            // 'quantity' => $product->quantity + $request->quantity,
        // ]); 

        $invProduct = GlobalInventory::find($request->product_id);
        $invProduct->update([
            'quantity' => $invProduct->quantity + $request->quantity,
        ]);
        
        return response()->json([
            'message' => $request->quantity . " of " . $product->name . "has been added to warhouse. Quantity is now" . $product->quantity,
        ]);
    }
        
}
