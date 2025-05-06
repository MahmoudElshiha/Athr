<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Product::all() as $product) {
            ProductImage::factory(4)->create([
                'product_id' => $product->id,
            ]);
        }
    }
}
