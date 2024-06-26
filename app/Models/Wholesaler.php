<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Wholesaler extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $fillable = [
        'owner_id', 
        'name', 
        'email',
        'address', 
        'is_active'
    ];

    public function warehouses() : HasMany 
    {
        return $this->hasMany(Warehouse::class); 
    }
}
