<?php

namespace App\Http\Controllers\Vendor;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\DataTables\ProductVariantDataTable;
use Illuminate\Support\Facades\Session;
class ProductVariantController extends Controller
{
    public function index(ProductVariantDataTable $dataTable, $id)
    {

        $product = Product::findOrFail($id);
        return $dataTable->with("id", $id)->render("vendor.product.variant.index", compact("product"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $productID)
    {
        $product = Product::findOrFail($productID);
        return view("vendor.product.variant.create", compact("product"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $productID)
    {
        $request->validate([
            "name" => "required",
            "status" => "required"
        ]);
        // Check only one variant is swipable
        if ($request->is_swipe) {
            $items = ProductVariant::where("product_id", $productID)->get();
            foreach ($items as $val) {
                $val->update(["is_swipe" => 0]);
            }
        }
        // Create new variant
        ProductVariant::create([
            "product_id" => $productID,
            "name" => $request->name,
            "status" => $request->status,
            "is_swipe" => $request->is_swipe ?? 0,
        ]);
        Session::flash("status", "Create Product Variant Successfully");
        return redirect()->route("vendor.product.variant.index", $productID);
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
    public function edit(string $productID, string $variantID)
    {
        $product = Product::findOrFail($productID);
        $variant = ProductVariant::findOrFail($variantID);
        return view("vendor.product.variant.edit", compact("product", "variant"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $productID, string $variantID)
    {
        $role = Auth::user()->role;
        $variant = ProductVariant::findOrFail($variantID);
        $request->validate([
            "name" => "required",
            "status" => "required"
        ]);
        // Check only one variant is swipable
        if ($request->is_swipe) {
            $items = ProductVariant::where("product_id", $productID)->get();
            foreach ($items as $val) {
                $val->update(["is_swipe" => 0]);
            }
        }
        // Update variant
        $variant->update([
            "name" => $request->name,
            "status" => $request->status,
            "is_swipe" => $request->is_swipe ?? 0,
        ]);
        Session::flash("status", "Update Product Variant Successfully");
        return redirect()->route("$role.product.variant.index", $productID);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $productID, string $variantID)
    {
        $variant = ProductVariant::findOrFail($variantID);
        // $variantItems = $variant->product_variant_item->all();
        // foreach ($variantItems as $item) {
        //     $item->delete();
        // };
        $variant->delete();
        return response([
            "status" => "success",
            "message" => "Deleted variant successfully",
            "is_empty" => isTableEmpty(ProductVariant::where("product_id",$productID)->get()),
        ]);
    }
    public function changeStatus(string $id)
    {
        $variant = ProductVariant::findOrFail($id);
        $newStatus = $variant->status == 0 ? 1 : 0;
        $variant->update(["status" => $newStatus]);
        return response([
            "status" => "success",
            "message" => "Update Product Variant Successfully",
        ]);
    }
    public function changeIsSwipe(string $id)
    {
        $variant = ProductVariant::findOrFail($id);
        $newStatus = $variant->is_swipe == 0 ? 1 : 0;
        $variant->update(["is_swipe" => $newStatus]);
        // Update all other variants to not swipable
        $items = ProductVariant::where("product_id", $variant->product_id)->get();
        foreach ($items as $val) {
            if ($val->id != $id) {
                $val->update(["is_swipe" => 0]);
            }
        }
        return response([
            "status" => "success",
            "message" => "Update Product Variant Successfully",
        ]);
    }

}
