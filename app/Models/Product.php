<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $guarded = [];

    public function ProductCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function productPenalties()
    {
        return $this->hasMany(ProductPenalty::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderProducts()
    {
        return $this->belongsToMany(OrderProduct::class);
    }

    public function cartProducts()
    {
        return $this->hasMany(cartProduct::class);
    }
}
