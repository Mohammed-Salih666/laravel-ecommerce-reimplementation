<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id', 
        'code', 
        'session_id',
        'total_price',
        'is_paid'
    ];

    public function details() {
        return $this->hasMany(OrderDetails::class);
    }
}
