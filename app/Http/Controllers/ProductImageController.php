<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductImage\StoreImageRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;

class ProductImageController extends Controller
{
    public function store(StoreImageRequest $request, $id)
    {
        $validated = $request->validated();

        $product = Product::find($id);

        if (!$product) {
            return api_error('Product not found', 404);
        }

        foreach ($validated['images'] as $image) {
            $product->productImages()->create([
                'product_id' => $product->id,
                'image' => $image,
            ]);
        }

        return api_success();
    }

    public function destroy(string $product_id, string $image_id)
    {
        $image = ProductImage::where('id', $image_id)
            ->where('product_id', $product_id)
            ->first();

        if (!$image) {
            return api_error('Image not found for the given product.', 404);
        }

        $image->delete();

        return api_success();
    }
}
