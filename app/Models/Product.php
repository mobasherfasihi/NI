<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sku',
        'name',
    ];

    /**
     * The users who purchased the product.
     */
    public function buyers()
    {
        return $this->belongsToMany(User::class, 'purchases', 'product_sku', 'user_id');
    }
}
