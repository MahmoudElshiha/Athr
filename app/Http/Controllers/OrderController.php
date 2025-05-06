<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $orders = $user->orders()->with(['orderProducts.product', 'orderProducts.product.productImages'])->get();
        return api_success(OrderResource::collection($orders));
    }

    public function store(CreateOrderRequest $request)
    {
        $validated = $request->validated();

        $user = auth()->user();

        $order = Order::create([
            'user_id' => $user->id
        ]);

        // attach products to the order
        foreach ($validated['order_products'] as $orderProduct) {
            $product = Product::find($orderProduct['product_id']);
            $quantity = $orderProduct['quantity'];
            $price = $product->price * $quantity;

            $order->orderProducts()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return api_success(new OrderResource($order));
    }

    public function show(string $id)
    {
        $user = auth()->user();

        $order = Order::where('id', $id)->where('user_id', $user->id)->first();
        if (!$order) {
            return api_error('Order not found', 404);
        }

        $order->load(['orderProducts.product', 'orderProducts.product.productImages']);

        return api_success(new OrderResource($order));
    }

    public function update(UpdateOrderRequest $request, string $id)
    {
        $validated = $request->validated();

        $user = auth()->user();

        $order = Order::where('id', $id)->where('user_id', $user->id)->first();

        if (!$order) {
            return api_error('Order not found', 404);
        }

        // detach existing products
        $order->orderProducts()->delete();

        // attach updated products to the order
        foreach ($validated['order_products'] as $orderProduct) {
            $product = Product::find($orderProduct['product_id']);
            $quantity = $orderProduct['quantity'];

            $order->orderProducts()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }
        return api_success(new OrderResource($order), 'Order updated successfully');
    }

    public function destroy(string $id)
    {
        $user = auth()->user();

        $order = Order::where('id', $id)->where('user_id', $user->id)->first();

        if (!$order) {
            return api_error('Order not found', 404);
        }

        $order->delete();
        return api_success(null, 'Order deleted successfully');
    }
}
