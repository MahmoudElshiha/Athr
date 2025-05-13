<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['productImages', 'productPenalties'])
            ->paginate(request('per_page', 10));

        return api_success(ProductResource::collection($products), paginate: true);
    }

    public function store(StoreProductRequest $request)
    {
        $valdiated = $request->validated();

        $product = Product::create(collect($valdiated)->except('images')->all());

        // create product images
        if (isset($valdiated['images'])) {
            $product->uploadImages($valdiated);
        }

        return api_success(new ProductResource($product));
    }

    public function show($product_id)
    {
        $product = Product::with(['productCategory', 'productImages', 'productPenalties', 'reviews'])->find($product_id);

        if (!$product) {
            return api_error('Product not found', 404);
        }


        return api_success(new ProductResource($product));
    }

    public function update(UpdateProductRequest $request, string $id)
    {
        $valdiated = $request->validated();

        $product = Product::find($id);
        if (!$product) {
            return api_error('Product not found', 404);
        }

        $product->update($valdiated);


        return api_success(new ProductResource($product));
    }


    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return api_error('Product not found', 404);
        }

        $product->delete();

        return api_success();
    }
}
