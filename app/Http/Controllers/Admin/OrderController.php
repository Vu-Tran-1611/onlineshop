<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendOrderNotificationMailJob;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Update order status based on order product status 
    public static function updateOrderStatus($id)
    {
        $order = Order::findOrFail($id);
        // Get Order Products by order ID 
        $orderProducts = OrderProduct::where("order_id", $id)->get();
        //Case 1 Pending - Default 
        //Case 2 Processing - If all order products are processing 
        if ($orderProducts->every(fn($item) => $item->status === 'confirmed' || $item->status === 'processing')) {
            $order->order_status = 'processing';
            SendOrderNotificationMailJob::dispatch($order->user, $order, 'confirmed');
        }
        //Case 3 Completed - If all order products are completed
        if ($orderProducts->every(fn($item) => $item->status === 'delivered')) {
            $order->order_status = 'delivered';
            SendOrderNotificationMailJob::dispatch($order->user, $order, 'delivered');
        }
        //Case 4 Cancelled - If any order product is cancelled
        if ($orderProducts->contains(fn($item) => $item->status === 'cancelled')) {
            $order->order_status = 'cancelled';
            SendOrderNotificationMailJob::dispatch($order->user, $order, 'cancelled');
        }

        $order->save();
    }
}
