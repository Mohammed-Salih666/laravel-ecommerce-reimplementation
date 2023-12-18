<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutController extends Controller
{
    
    public function checkout() 
    {

        Stripe::setApiKey(env('STRIPE_SECRET-KEY'));

        $cart = Auth::user()->cart;
        $cartDetails = $cart->details(); 

        $order = new Order(); 
        $order->cart_id = $cart->id; 
        $order->code = Str::random(5); 

        $totalPrice = 0; 

        foreach($cartDetails as $detail) {
            $product = Product::find($detail->product_id); 
            $quantity = $detail->quantity; 
            $totalPrice += $product->price; 
            
            OrderDetails::create([
                'order_id' => $order->id, 
                'product_id' => $product->id, 
                'quantity' => $detail->quantity
            ]);

            $lineItems = [[
                'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => $product->name,
                ],
                'unit_amount' => $product->price,
                ],
                'quantity' => $quantity,
            ]];
        }

        $session = Session::create
        ([
        'line_items' => $lineItems,
        'mode' => 'payment',
        'success_url' => route('checkout.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}",
        'cancel_url' => route('checkout.cancel', [], true),
        ]);

        $order->session_id = $session->is; 
        $order->total_price = $totalPrice; 
        $order->save(); 
        
        return redirect($session->url);

    }

    public function success(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $sessionId = $request->get('session_id');

        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            if ($session == null) {
                throw new NotFoundHttpException;
            }

            $order = Order::where('session_id', $session->id)->first();
            if ($order == null) {
                throw new NotFoundHttpException();
            }
            if (!$order->is_paid) {
                $order->is_paid = true;
                $order->save();
            }

            return response()->json([
                'order_id' => $order->id, 
                'details' => $order->details(), 
                'status' => $order->is_paid, 
            ]);

        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }

    }

    public function cancel() 
    {
        
    }

}