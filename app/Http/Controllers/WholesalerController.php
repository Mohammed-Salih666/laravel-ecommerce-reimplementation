<?php

namespace App\Http\Controllers;

use App\Models\Wholesaler;
use App\Http\Requests\WholesalerRequest;

class WholesalerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Wholesaler::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WholesalerRequest $request)
    {
        $wholesaler = Wholesaler::create($request->validated());
        return response()->json([
            'message' => 'wholesaler created',
            'data' => $wholesaler
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(WholeSaler $wholesaler)
    {
        return Wholesaler::where('id', $wholesaler->id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WholesalerRequest $request, Wholesaler $wholesaler)
    {
        $saler = Wholesaler::findOrFail($wholesaler->id);
        $saler->update($request->validated()); 
        return response()->json([
            'message' => 'updated', 
            'data' => $saler
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wholesaler $wholesaler)
    {
        Wholesaler::destroy($wholesaler->id); 
        return response()->json([
            'message' => 'deleted', 
            'data' => $wholesaler
        ]);
    }
    
    public function getWarehouses($wholesalerId)
    {
        return Wholesaler::findOrFail($wholesalerId)->warehouses;
    }


}
