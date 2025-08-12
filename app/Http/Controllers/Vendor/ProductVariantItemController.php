<?php

namespace App\Http\Controllers\Vendor;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\ProductVariantItem;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use App\DataTables\ProductVariantItemDataTable;
class ProductVariantItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductVariantItemDataTable $dataTable, string $productID, string $variantID)
    {
        $variant = ProductVariant::findOrFail($variantID);
        return $dataTable->with("variantID", $variantID)->render('vendor.product.variant.item.index', compact("productID", "variant"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $productID, string $variantID)
    {
        $variant = ProductVariant::findOrFail($variantID);

        return view("vendor.product.variant.item.create", compact("productID", "variant"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $productID, string $variantID)
    {
        $variant = ProductVariant::findOrFail($variantID);
        $request->validate([
            "name" => ["required"],
            "price" => ["required", "integer"],
            "is_default" => ["required"],
            "status" => ["required"],
        ]);
        // Check only one item is default
        if ($request->is_default == 1) {
            $items = ProductVariantItem::all();
            foreach ($items as $val) {
                $val->update(["is_default" => 0]);
            }
        }
        ProductVariantItem::create([
            "product_variant_id" => $variantID,
            "name" => $request->name,
            "price" => $request->price,
            "is_default" => $request->is_default,
            "status" => $request->status,
        ]);

        Session::flash("status", "Create Product Variant Item Successfully");
        return redirect()->route('vendor.product.variant.item.index', [$productID, $variant]);
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
    public function edit(string $productID, string $variantID, string $itemID)
    {
        $product = Product::findOrFail($productID);
        $variant = ProductVariant::findOrFail($variantID);
        $item = ProductVariantItem::findOrFail($itemID);
        return view("vendor.product.variant.item.edit", compact("product", "variant", "item"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $productID, string $variantID, string $itemID)
    {
        $role = Auth::user()->role;
        $product = Product::findOrFail($productID);
        $variant = ProductVariant::findOrFail($variantID);
        $item = ProductVariantItem::findOrFail($itemID);
        $request->validate([
            "name" => ["required"],
            "price" => ["required", "integer"],
            "is_default" => ["required"],
            "status" => ["required"],
        ]);
        // Check only one item is default
        if ($request->is_default == 1) {
            $items = ProductVariantItem::all();
            foreach ($items as $val) {
                $val->update(["is_default" => 0]);
            }
        }
        // Update item
        $item->update([
            "variant_id" => $variantID,
            "name" => $request->name,
            "price" => $request->price,
            "is_default" => $request->is_default,
            "status" => $request->status,
        ]);
        Session::flash("status", "Update Product Variant Item Successfully");
        return redirect()->route("$role.product.variant.item.index", [$productID, $variant]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $productID, string $variantID, string $itemID)
    {
        $item = ProductVariantItem::findOrFail($itemID);
        $item->delete();
        return response([
            "status" => "success",
            "message" => "Deleted Product Variant Item Successfully",
            "is_empty" => isTableEmpty(ProductVariantItem::where("product_variant_id", $variantID)->get()),
        ]);
    }
    public function changeStatus(string $variantID)
    {
        $variant = ProductVariantItem::findOrFail($variantID);
        $newStatus = $variant->status == 1 ? 0 : 1;
        $variant->update(["status" => $newStatus]);
        return response([
            "status" => "success",
            "message" => "Update Product Variant Item Successfully",
        ]);
    }

    public function isDefault(string $itemID)
    {
        $item = ProductVariantItem::findOrFail($itemID);

        if ($item->is_default == 1)
            return response([
                "status" => "Update Product Vartiant Item Status Unsuccessfully"
            ]);

        $items = ProductVariantItem::all();
        foreach ($items as $val) {
            $val->update(["is_default" => 0]);
        }
        $item->update(["is_default" => 1]);
        return response([
            "status" => "Update Product Vartiant Item Status Successfully"
        ]);
    }
}
