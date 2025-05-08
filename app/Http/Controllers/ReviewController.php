<?php

namespace App\Http\Controllers;

use App\Http\Requests\Review\StoreReviewRequest;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request, $product_id)
    {
        $validated = $request->validated();

        $validated['user_id'] = auth()->user()->id;
        $validated['product_id'] = $product_id;

        // Check if the product exists
        $product = Product::find($product_id);
        if (!$product) {
            return api_error('Product not found', 404);
        }

        // Check if the user has already reviewed the product
        $existingReview = Review::where('user_id', $validated['user_id'])
            ->where('product_id', $product_id)
            ->first();

        if ($existingReview) {
            return api_error('You have already reviewed this product', 400);
        }

        $review = Review::create($validated);

        return api_success();
    }


    public function destroy(string $product_id, string $review_id)
    {
        $review = Review::where('id', $review_id)
            ->where('product_id', $product_id)
            ->first();

        if (!$review) {
            return api_error('Review not found for the given product', 404);
        }

        // Delete the review
        $review->delete();

        return api_success();
    }
}
