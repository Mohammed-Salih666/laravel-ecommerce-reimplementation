<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::all(); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('create.user.form'); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        // $data = $request->validated(); 
        // $user = new User(); 
        // $user->fill($data); 
        // $user->save(); 

        $user = new User(); 
        $data = $this->prepare($request->validated(), $user->getFillable()); 
        $user->fill($data); 
        $user->save(); 

        return response()->json([
            'user' => $user, 
        ]); 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return User::findOrFail($id); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('edit.user.form');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        // $user = User::findOrFail($user); 
        $data = $this->prepare($request->validated(), $user->getFillable()); 
        $user->fill($data); 
        $user->save(); 

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete(); 
        return response()->json([
            'user' => $user->id,
            'status' => 'deleted'
        ]); 
    }
}
