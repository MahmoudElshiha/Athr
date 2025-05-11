<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductImage\StoreImageRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function store(StoreImageRequest $request, $id): JsonResponse
    {
        $validated = $request->validated();

        $product = Product::find($id);

        if (!$product) {
            return api_error('Product not found', 404);
        }

        $product->uploadImages($validated);

        return api_success();
    }

    public function destroy(string $product_id, string $image_id): JsonResponse
    {
        $image = ProductImage::where('id', '=', $image_id, 'and')
            ->where('product_id', '=', $product_id, 'and')
            ->first();

        if (!$image) {
            return api_error('Image not found for the given product.', 404);
        }

        if (file_exists(storage_path('app/public/' . $image->image))) {
            unlink(storage_path('app/public/' . $image->image));
        }

        $image->delete();

        return api_success();
    }
}
