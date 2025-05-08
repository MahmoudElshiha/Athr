<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    public static   $categories = [
        'Window Air Conditioners',
        'Portable Air Conditioners',
        'Split Air Conditioners',
        'Central Air Conditioners',
        'Smart Air Conditioners',
        'Hybrid Air Conditioners',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
