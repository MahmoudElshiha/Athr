<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Http\Resources\CartResource;
use App\Models\cartProduct;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $cart = Cart::with(['cartProducts.product', 'cartProducts.product.productImages'])
            ->where('user_id', $user->id)
            ->first();

        if (!$cart) {
            return api_error('Cart not found', 404);
        }
        return api_success(new CartResource($cart), 'Cart retrieved successfully');
    }

    public function store(AddToCartRequest $request)
    {
        $validated = $request->validated();


        return DB::transaction(function () use ($validated) {
            $cart = Cart::firstOrCreate(['user_id' => auth()->user()->id]);

            $cartProduct = $cart->cartProducts()->firstOrNew([
                'product_id' => $validated['product_id']
            ]);

            $cartProduct->quantity = ($cartProduct->quantity ?? 0) + $validated['quantity'];
            $cartProduct->save();

            return api_success(new CartResource($cartProduct->load('product')), 'Product added to cart', 201);
        });
    }

    public function update(UpdateCartRequest $request, string $id)
    {
        $validated = $request->validated();


        $cart = Cart::where('user_id', auth()->id)->first();

        if (!$cart) {
            return api_error('Cart not found', 404);
        }

        $cartProduct = $cart->cartProducts()->where('product_id', $id)->first();

        if (!$cartProduct) {
            return api_error('Product not found in cart', 404);
        }

        $cartProduct->quantity = $validated['quantity'];
        $cartProduct->save();

        return api_success(new CartProduct($cartProduct), 'Cart updated successfully');
    }

    public function destroy(string $id)
    {


        $cart = Cart::where('user_id', auth()->id)->first();

        if (!$cart) {
            return api_error('Cart not found', 404);
        }

        $deleted = $cart->cartProducts()->where('product_id', $id)->delete();

        if (!$deleted) {
            return api_error('Product not found in cart', 404);
        }

        return api_success(null, 'Product removed from cart');
    }
}
