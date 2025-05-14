<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'products' => $this->whenLoaded('cartProducts', function () {
                return ProductResource::collection(
                    $this->cartProducts->product
                );
            }),
            'product_count' => $this->whenLoaded(
                'cartProducts',
                fn() => $this->cartProducts->count()
            ),
            'total_price' => $this->whenLoaded(
                'cartProducts',
                fn() => $this->cartProducts->sum(fn($product) =>  $product->quantity)
            ),
        ];
    }
}
