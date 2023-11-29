<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\CartRequest;
use App\Models\CartDetail;

class CartController extends Controller
{
    public function index(User $user) {
        if($user->cart() == null) 
        {
            return response()->json([
             'message' => 'Cart is empty'   
            ]);
        }
        return $user->cart(); 
    }

    public function add(User $user, CartRequest $request) {

        if($user->cart() == null) {
            $cart = new Cart([
                'user_id' => $user->id, 
                'code' => Str::random(5), 
                'is_active' => true
            ]);
        }

        $cartDetail = new CartDetail(); 
        $data = $this->prepare($request->validated(), $cartDetail->getFillable());
        $cartDetail->fill($data); 
        $cartDetail->save(); 
        
        return response()->json([
            'added item details' => $cartDetail, 
            'message' => 'Product has been added'
        ]);
        
    }

    public function remove(User $user) {
        $cart = $user->cart(); 
        $cart->delete(); 

        return response()->json([
            'cart' => $cart,
            'message' => 'deleted'
        ]);
    }

    private function getOrCreateCart(User $user, CartRequest $request) {

        if($user->cart() != null) return $user->cart(); 

        $cart = new Cart(); 
        $data = $this->prepare($request->validated(), $cart->getFillable()); 
        $cart->fill($data); 
        $cart->save(); 
        
        return $cart; 
    }
}
