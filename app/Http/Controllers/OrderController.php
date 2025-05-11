<?php

namespace App\Http\Controllers;

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


    public function destroy(string $id)
    {
        $user = auth()->user();

        $order = Order::where('id', $id)->where('user_id', $user->id)->first();

        if (!$order) {
            return api_error('Order not found', 404);
        }

        $order->delete();
        return api_success();
    }
}
