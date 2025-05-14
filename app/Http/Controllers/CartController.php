<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Http\Resources\CartResource;
use App\Models\CartProduct;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $cart = Cart::with(['cartProducts.product', 'cartProducts.product.productImages'])
            ->firstOrCreate(['user_id' => $user->id]);

        return api_success(new CartResource($cart));
    }

    public function store(AddToCartRequest $request)
    {
        $validated = $request->validated();


        return DB::transaction(function () use ($validated) {
            $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

            $cartProduct = $cart->cartProducts()->firstOrNew([
                'product_id' => $validated['product_id'],
                'cart_id' => $cart->id,
            ]);

            $newQuantity = ($cartProduct->exists ? $cartProduct->quantity : 0) + $validated['quantity'];

            $product = Product::findOrFail($validated['product_id']);
            if ($validated['quantity'] > $product->quantity) {
                return api_error('Not enough stock available', 422);
            }
            // Update cart
            $cartProduct->quantity = $newQuantity;
            $cartProduct->save();


            return api_success(new CartResource(
                $cartProduct->cart->fresh()->load('cartProducts')
            ));
        });
    }

    public function update(UpdateCartRequest $request, string $id)
    {
        $validated = $request->validated();


        $cart = Cart::where('user_id', auth()->id)->first();

        if (!$cart) {
            return api_error('Cart not found', 404);
        }

        $cartProduct = $cart->cartProducts()->where('product_id', $id)->firstOrFail();

        $product = Product::findOrFail($id);
        if ($validated['quantity'] > $product->quantity) {
            return api_error('Not enough stock available', 422);
        }

        $cartProduct->quantity = $validated['quantity'];
        $cartProduct->save();

        return api_success(new CartProduct($cartProduct));
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

        return api_success();
    }
}
