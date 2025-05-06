<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderProductSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Order::all() as $order) {
            $products = Product::all()->random(rand(2, 5));
            foreach ($products as $product) {
                OrderProduct::factory()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                ]);
            }
        }
    }
}
