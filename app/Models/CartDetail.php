<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id', 
        'product_id',
        'qunatity',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function cart() : BelongsTo {
        return $this->belongsTo(Cart::class);
    }
}
