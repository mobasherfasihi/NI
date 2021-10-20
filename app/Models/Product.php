<?php

namespace App\Models;

use App\Traits\SluggableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, SluggableTrait;

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
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'sku' => [
                'source' => 'name',
                'onUpdate' => true
            ]
        ];
    }

    /**
     * The users who purchased the product.
     */
    public function buyers()
    {
        return $this->belongsToMany(User::class, 'purchases', 'product_sku', 'user_id');
    }
}
