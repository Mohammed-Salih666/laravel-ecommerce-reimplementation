<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Traits\MultiLanguage; 

class Product extends Model
{
    use HasFactory, MultiLanguage;

    protected $fillable = [
        'category_id', 
        'name_en',
        'name_ar', 
        'price', 
        'old_price', 
        'description_en', 
        'description_ar',
        'slug', 
        'is_active'
    ];

    protected $multi_lang = [
        'name',
        'description'
    ];

    public function category() : BelongsTo {
        return $this->belongsTo(Category::class); 
    }

    public function images() {
        return $this->hasMany(ProductImage::class); 
    }
    // public function category() : HasOne {
    //     return $this->hasOne(Category::class); 
    // }
}
