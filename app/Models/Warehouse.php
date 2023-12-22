<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'wholesaler_id', 
        'name',
        'address',
        'latitude', 
        'longitude', 
        'slug', 
        'is_active', 
    ];

    public function products() : BelongsToMany 
    {
        return $this->belongsToMany(Product::class, 'warehouse_product', 'product_id', 'warehouse_id')->withPivot(['quantity', 'created_at', 'updated_at', 'is_active']); 
    }

    public function owner() : BelongsTo 
    {
        return $this->belongsTo(Wholesaler::class, 'wholesaler_id'); 
    }
}
