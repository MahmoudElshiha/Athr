<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->product_category_id,
            'category_name' => $this->productCategory->name,
            'name' => $this->name,
            'manufacturer' => $this->manufacturer,
            'description' => $this->description,
            'price' => $this->price,
            'is_favorite' => $this->isFavourite(),
            'product_images' => ProductImageResource::collection($this->whenLoaded('productImages')),
            'product_penalties' => ProductPenaltyResource::collection($this->whenLoaded('productPenalties')),
        ];
    }
}
