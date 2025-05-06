<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'products' => $this->whenLoaded('orderProducts', function () {
                return ProductResource::collection($this->orderProducts->pluck('product'));
            })
        ];
    }
}
