<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\DataTables\CouponDataTable;
use Illuminate\Support\Facades\Session;
class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CouponDataTable $dataTable)
    {
        return $dataTable->render("admin.coupons.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.coupons.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => ["required"],
            "code" => ["required"],
            "quantity" => ["required", "integer"],
            "max_use" => ["required", "integer"],
            "start_date" => ["required"],
            "end_date" => ["required"],
            "discount" => ["required", "integer"],
            "status" => ["required"],
        ]);
        Coupon::create([
            "name" => $request->name,
            "code" => $request->code,
            "quantity" => $request->quantity,
            "max_use" => $request->max_use,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
            "discount" => $request->discount,
            "discount_type" => $request->discount_type,
            "status" => $request->status,
            "total_used" => 0
        ]);
        Session::flash("status", "success");
        return redirect()->route("admin.coupons.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        return view("admin.coupons.edit", compact("coupon"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $request->validate([
            "name" => ["required"],
            "code" => ["required"],
            "quantity" => ["required", "integer"],
            "max_use" => ["required", "integer"],
            "start_date" => ["required"],
            "end_date" => ["required"],
            "discount" => ["required", "integer"],
            "status" => ["required"],
        ]);
        $coupon->update([
            "name" => $request->name,
            "code" => $request->code,
            "quantity" => $request->quantity,
            "max_use" => $request->max_use,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
            "discount" => $request->discount,
            "discount_type" => $request->discount_type,
            "status" => $request->status,
            "total_used" => 0
        ]);
        Session::flash("status", "Update General Setting Successfully");
        return redirect()->route("admin.coupons.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return response([
            "status" => "success",
            "message" => "Delete slider successfully",
        ]);
    }
    public function changeStatus(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $newStatus = $coupon->status == 0 ? 1 : 0;
        $coupon->update(["status" => $newStatus]);
        return response([
            "status" => "success",
            "message" => "Update status successfully"
        ]);
    }
}
