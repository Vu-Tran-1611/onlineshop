<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Orders";
        $categories = Category::get();

        // Fetch orders for the authenticated user
        $orders = auth()->user()->orders()->with('orderProducts')->paginate(5);
        return view('frontend.pages.profile-orders', compact('title', 'orders', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $title = "Order Details";
        $categories = Category::get();
        $user = auth()->user();
        // Fetch the order by ID for the authenticated user
        $order = auth()->user()->orders()->with(['orderProducts', 'userAddress'])->findOrFail($id);
        $quantity = $order->orderProducts->sum('qty');
        $order->quantity = $quantity; // Add quantity to the order object
        // Ensure the order has a valid payment method      
        return view('frontend.pages.profile-order-details', compact('title', 'user', 'order', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
