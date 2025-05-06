<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function Products()
    {
        return $this->hasMany(Product::class);
    }
}
