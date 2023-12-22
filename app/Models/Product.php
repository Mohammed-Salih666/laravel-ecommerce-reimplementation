<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 
        'name', 
        'price', 
        'old_price', 
        'description', 
        'slug', 
        'is_active'
    ];

    public function category() : BelongsTo {
        return $this->belongsTo(Category::class); 
    }

    public function images() {
        return $this->hasMany(ProductImage::class); 
    }

    public function warehouses() : BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'warehouse_product', 'product_id', 'warehouse_id')->withPivot(['quantity', 'created_at', 'updated_at', 'is_active']); 
    }
    // public function category() : HasOne {
    //     return $this->hasOne(Category::class); 
    // }
}
