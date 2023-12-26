<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Wholesaler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $wholesalerId = $request->route('wholesaler');
        $wholesaler = \App\Models\Wholesaler::find($wholesalerId);
        
        $user = $request->user(); 

        if($user && $user->hasRole('wholesaler')) 
        {
            if($wholesaler->owner_id == $user->id)
            {
                return $next($request); 
            }
        }
        
        return response()->json([
            'error' => 'unauthorized'
        ], 403);    }
}
