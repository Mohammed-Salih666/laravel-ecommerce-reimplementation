<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function prepare(Request $request, $fillables) 
    {
        $data = array();
        foreach($fillables as $fillable) {
            if($request->has($fillable)) {
                $data[$fillable] = $request->get($fillable); 
            }
        }

        return $data;
    }
}
