<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\GlobalInventory;
use App\Http\Requests\WarehouseRequest;
use App\Notifications\WholesalerEmptyWarehouse;

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
    public function show(string $warehouseId)
    {
        return Warehouse::findOrFail($warehouseId); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WarehouseRequest $request, string $warehouseId)
    {
        $warehouse = Warehouse::findOrFail($warehouseId); 
        $warehouse->update($request->validated()); 

        return response()->json([
            'message' => 'warehouse updated',
            'data' => $warehouse
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $warehouseId)
    {
        Warehouse::destroy($warehouseId);  

        return response()->json([
            'message' => 'warehouse deleted',
            'data' => ['warehouse' => $warehouseId]
        ]);
    }

    //Add a quantity of products to a designated warehouse. 
    public function addToWarehouse(WarehouseRequest $request) 
    {
        $warehouse = Warehouse::where('id', $request->warehouse_id)->first(); 
        $product = $warehouse->products()->where('product_id', $request->product_id)->first(); 

        if($product == null) 
        {
            return response()->json([
                'message' => 'Product not found. Please insert the product to the warehouse first.'
            ], 404);
        }
        $warehouse->products()->updateExistingPivot($product->id, ['quantity' => $product->pivot->quantity + $request->quantity]);
        if(!$product->is_active)
        {
            $warehouse->products()->updateExistingPivot($product->id, ['is_active' => true]);
        }

        $invProduct = GlobalInventory::find($request->product_id);
        $invProduct->update([
            'quantity' => $invProduct->quantity + $request->quantity,
        ]);
        
        return response()->json([
            'message' => $request->quantity . " of " . $product->name . " has been added to warhouse. Quantity is now " . $product->quantity . ".",
        ]);
    }

    //insert a new product into the warehouse_product pivot table
    public function insertNewProduct(WarehouseRequest $request)
    {
        $warehouseId = $request->warehouse_id; 
        $productId = $request->product_id; 
        $quantity = $request->quantity; 
        if(!Product::where('id', $productId)->exists()){
            return response()->json([
                'message' => 'Error. The product you are trying to insert does not exist. '
            ], 404);
        }
        
        $warehouse = Warehouse::find($warehouseId); 
        $warehouse->products()->attach($productId, ['quantity' => $quantity]);

        $invProduct = GlobalInventory::where('product_id', $productId)->first(); 
        $invProduct->update([
            'quantity' => $invProduct->quantity + $quantity,
        ]);

        return response()->json([
            'message' => 'Product: ' . $productId . ' has been added to the warehouse',
            'quantity' => $quantity
        ]);
    }
 
    public function removeFromWarehouse(WarehouseRequest $request)
    {
        $warehouse = Warehouse::where('id', $request->warehouse_id)->first(); 
        $product = $warehouse->products()->where('product_id', $request->product_id)->first(); 

        if($product == null) 
        {
            return response()->json([
                'message' => 'Product not found. Cannot deduct any quantity.'
            ], 404);
        }

        $warehouse->products()->updateExistingPivot($product->id, ['quantity' => $product->quantity - $request->quantity]);
        
        if($product->quantity == 0)
        {
            //change activity status: 
            $warehouse->products()->updateExistingPivot($product->id, ['is_active' => false]);

            //notify wholesaler: 
            $warehouse->wholesaler()->notify(new WholesalerEmptyWarehouse($warehouse->pivot()));
        }

        $invProduct = GlobalInventory::find($request->product_id);
        $invProduct->update([
            'quantity' => $invProduct->quantity - $request->quantity,
        ]);
        
        if($invProduct->quantity == 0)
        {
            //change activity status: 
            Product::find($invProduct->id)->update([
                'is_active' => false
            ]);
            
            //notify responsible wholesalers
            foreach($invProduct->warehouses() as $warehouse)
            {
                $warehouse->wholesaler()->notify(new WholesalerEmptyWarehouse($warehouse->pivot())); 
            }
        }

        return response()->json([
            'message' => $request->quantity . " of " . $product->name . "has been added to warhouse. Quantity is now" . $product->quantity,
        ]);
    }

    public function deleteWarehouseProduct(WarehouseRequest $request) 
    {
        $warehouseId = $request->warehouse_id; 
        $productId = $request->product_id; 

        $warehouse = Warehouse::where('id', $warehouseId)->first(); 
        $product = $warehouse->products()->where('product_id', $productId);

        if($warehouse == null || $product == null) 
        {
            return response()->json([
                'message' => 'Warehouse or product does not exist.'
            ], 404);
        }

        $invProduct = GlobalInventory::find($productId);
        $invProduct->update([
            'quantity' => $invProduct->quantity - $product->quantity,
        ]);
        $warehouse->products()->detach($productId);

        return response()->json([
            'message' => 'Product has been permanently removed from warehouse', 
            'data' => $product,
        ]);
    }   
}
