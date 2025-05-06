<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductPenalty;
use Illuminate\Database\Seeder;

class ProductPenaltySeeder extends Seeder
{
    public function run(): void
    {
        foreach (Product::all() as $product) {
            ProductPenalty::factory(3)->create([
                'product_id' => $product->id,
            ]);
        }
    }
}
