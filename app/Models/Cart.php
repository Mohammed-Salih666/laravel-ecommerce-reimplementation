<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'code', 
        'is_active'
    ]; 

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class); 
    }
}
