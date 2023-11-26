<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Address;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Address::all(); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('create.address.form'); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAddressRequest $request)
    {
        $address = new Address() ;
        $data = $this->prepare($request->validated(), $address->getFillable()); 
        $address->fill($data); 
        $address->save(); 

        return response()->json([
            'address' => $address,
            'message' => 'address has been added',
        ]); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        return Address::findOrFail($address); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Address $address)
    {
        return view('address.edit.form'); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAddressRequest $request, Address $address)
    {
        $data = $this->prepare($request->validated(), $address->getFillable()); 
        $address->fill($data); 
        $address->save(); 

        return response()->json([
            'new address' => $address
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        $address->delete(); 
        return response()->json([
            'address' => $address, 
            'status' => 'deleted', 
        ]); 
    }
}
