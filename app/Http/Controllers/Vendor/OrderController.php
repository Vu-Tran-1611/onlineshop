<?php

namespace App\Http\Controllers\Vendor;

use App\DataTables\OrderProductsDataTable;
use App\DataTables\OrdersDataTable;
use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(OrdersDataTable $dataTable, Request $request)
    {
        // Fetch orders for the authenticated vendor
        $vendorId = auth()->user()->id; // Assuming the vendor ID is stored in the authenticated user
        $orderType = $request->get("orderType", "all");
        return $dataTable->with("vendorId", $vendorId)->with("orderType", $orderType)->render("vendor.order.index");
    }


    public function show(OrderProductsDataTable $dataTable, $id)
    {
        return $dataTable->render("vendor.order.show", compact("id"));
    }
}
