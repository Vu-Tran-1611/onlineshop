<?php

namespace App\Http\Controllers\Vendor;

use App\DataTables\ProductDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Traits\UploadTrait;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Auth;
class ProductController extends Controller
{
    use UploadTrait;


    /**
     * Display a listing of the resource.
     */
    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render("vendor.product.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view("vendor.product.create", compact("categories", "brands"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => ["required"],
            "price" => ["integer"],
            "offer_price" => ["integer"],
            'thumb_image' => ["image", "required"],
            "category_id" => ["required"],
            "brand_id" => ["required"],
            "qty" => ["required", "integer"],
            "short_description" => ["required"],
            "long_description" => ["required"],
            "status" => ["required"],
            "seo_title" => ["required"],
            "seo_description" => ["required"],
        ]);

        $path = $this->uploadImage($request, "uploads", "thumb_image");
        Product::create([
            "name" => $request->name,
            "thumb_image" =>  $path,
            "shop_profile_id" => Auth::user()->shop_profile->id,
            "category_id" => $request->category_id,
            "sub_category_id" => $request->sub_category_id,
            "brand_id" => $request->brand_id,
            "qty" => $request->qty,
            "short_description" => $request->short_description,
            "long_description" => $request->long_description,
            "video_link" => $request->video_link,
            "sku" => $request->sku,
            "price" => $request->price,
            "offer_price" => $request->offer_price,
            "offer_start_price" => $request->offer_start_price,
            "offer_end_price" => $request->offer_end_price,
            "status" => $request->status,
            "is_approved" => -1,
            "seo_title" => $request->seo_title,
            "seo_description" => $request->seo_description,
            "slug" => Str::slug($request->name),
            "product_type" => $request->product_type,
        ]);
        Session::flash("status", "Create product successfully");
        return redirect()->route("vendor.product.index");
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
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $subCategories = SubCategory::where("category_id", $product->category_id)->get();
        $brands = Brand::get();
        return view("vendor.product.edit", [
            "product" => $product,
            "brands" => $brands,
            "categories" => $categories,
            "subCategories" => $subCategories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Auth::user()->role;
        $product = Product::findOrFail($id);
        $request->validate([
            "name" => ["required"],
            "price" => ["integer"],
            "offer_price" => ["integer"],
            'thumb_image' => ["image"],
            "category_id" => ["required"],
            "brand_id" => ["required"],
            "qty" => ["required", "integer"],
            "short_description" => ["required"],
            "long_description" => ["required"],
            "status" => ["required"],
            "seo_title" => ["required"],
            "seo_description" => ["required"],
        ]);
        $newPath = $this->updateImage($request, $product->thumb_image, "uploads", "thumb_image");
        $product->update([
            "name" => $request->name,
            "thumb_image" => $newPath ?? $product->thumb_image,
            // "shop_profile_id" => Auth::user()->shop_profile->id,
            "category_id" => $request->category_id,
            "sub_category_id" => $request->sub_category_id,
            "brand_id" => $request->brand_id,
            "qty" => $request->qty,
            "short_description" => $request->short_description,
            "long_description" => $request->long_description,
            "video_link" => $request->video_link,
            "sku" => $request->sku,
            "price" => $request->price,
            "offer_price" => $request->offer_price,
            "offer_start_price" => $request->offer_start_price,
            "offer_end_price" => $request->offer_end_price,
            "status" => $request->status,
            "seo_title" => $request->seo_title,
            "seo_description" => $request->seo_description,
            "slug" => Str::slug($request->name),
            "product_type" => $request->product_type,
        ]);
        Session::flash("status", "Update product successfully");
        if($role == "vendor") return redirect()->route("vendor.product.index");
        return redirect()->route("admin.product_from_vendor.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        // $variants = $product->productVariants->all();
        // foreach ($variants as $variant) {
        //     $items = $variant->product_variant_item->all();
        //     foreach ($items as $item) {
        //         $item->delete();
        //     }
        //     $variant->delete();
        // }
        $images = $product->productImageGalleries->all();
        foreach ($images as $image) {
            $this->deleteImage($image->image);
            $image->delete();
        };
        $product->delete();

        return response([
            "status" => "success",
            "message" => "Deleted Product Successfully",
            "is_empty" => isTableEmpty(Product::get()),
        ]);
    }
    public function changeType(Request $req)
    {
        $id = $req->productID;
        $product = Product::findOrFail($id);
        $newType = $req->productType;
        $product->update(["product_type" => $newType]);
        return response([
            "status" => "Update Product Type Successfully"
        ]);
    }

    public function changeStatus(string $id)
    {
        $product = Product::findOrFail($id);
        $newStatus = $product->status == 1 ? 0 : 1;
        $product->update(["status" => $newStatus]);
        return response([
            "status" => "success",
            "message" => "Update Product Status Successfully"
        ]);
    }
}
