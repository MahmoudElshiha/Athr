<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['productImages', 'productPenalties'])->get();
        return api_success(ProductResource::collection($products));
    }

    public function store(StoreProductRequest $request)
    {
        $valdiated = $request->validated();

        $product = Product::create($valdiated);

        // create product images
        if (isset($valdiated['images'])) {
            foreach ($valdiated['images'] as $image) {
                $product->productImages()->create([
                    'product_id' => $product->id,
                    'image' => $image,
                ]);
            }
        }

        return api_success(new ProductResource($product), 'Product added successfully', 201);
    }

    public function show($product_id)
    {
        $product = Product::with(['productCategory', 'productImages', 'productPenalties', 'reviews'])->find($product_id);

        if (!$product) {
            return api_error('Product not found', 404);
        }


        return api_success(new ProductResource($product), 'Product added successfully', 201);
    }

    public function update(UpdateProductRequest $request, string $id)
    {
        $valdiated = $request->validated();

        $product = Product::find($id);
        if (!$product) {
            return api_error('Product not found', 404);
        }

        $product->update($valdiated);


        return api_success(new ProductResource($product), 'Product updated successfully');
    }


    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return api_error('Product not found', 404);
        }

        $product->delete();

        return api_success(null, 'Product deleted successfully');
    }
}
