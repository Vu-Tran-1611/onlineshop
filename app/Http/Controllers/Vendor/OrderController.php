<?php

namespace App\Http\Controllers\Vendor;

use App\DataTables\OrderProductsDataTable;
use App\DataTables\OrdersDataTable;
use App\Http\Controllers\admin\OrderController as AdminOrderController;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function index(OrdersDataTable $dataTable, Request $request)
    {
        // Fetch orders for the authenticated vendor
        $user = auth()->user();
        $vendorId = $user->shop_profile->id;

        $orderType = $request->get("orderType", "all");
        return $dataTable->with("vendorId", $vendorId)->with("orderType", $orderType)->render("vendor.order.index");
    }


    public function show($id)
    {
        $vendorId = auth()->user()->shop_profile->id;
        $order = Order::where("id", $id)->first();
        $orderProducts = $order->orderProductsByVendor($vendorId)->where("order_id", $id)->get();
        $orderAddress = $order->userAddress;
        $total = $order->orderProductTotalByVendor($vendorId);
        return view("vendor.order.show", [
            "order" => $order,
            "address" => $orderAddress,
            "total" => $total,
            "orderProducts" => $orderProducts,
        ]);
    }

    // Update order product status
    public function updateStatus(Request $request, $id)
    {

        $vendorId = auth()->user()->shop_profile->id;
        $order = Order::findOrFail($id);
        $orderProducts = $order->orderProductsByVendor($vendorId)->where("order_id", $id)->get();
        foreach ($orderProducts as $product) {
            $product->status = $request->input("status_{$product->id}");
            $product->save();
        }
        // Update order status based on order product status
        AdminOrderController::updateOrderStatus($id);
        // Flash message for successful update
        Session::flash("status", "Update Order Products successfully");
        return self::show($id);
    }
}
