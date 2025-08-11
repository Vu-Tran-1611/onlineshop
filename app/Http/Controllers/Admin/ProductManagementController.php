<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Traits\UploadTrait;
class ProductManagementController extends Controller
{
    use UploadTrait;
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $subCategories = SubCategory::where("category_id", $product->category_id)->get();
        $brands = Brand::get();
        return view("admin.product.edit", [
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
        if ($product->vendor->user_id == Auth::user()->id)
            return redirect()->route("admin.product.index");
        else return redirect()->route("admin.product_from_vendor.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $variants = $product->productVariants;
        if($variants) {
            foreach ($variants as $variant) {
                $items = $variant->product_variant_item;
                foreach ($items as $item) {
                    $item->delete();
                }
                $variant->delete();
            }
        }
        $images = $product->productImageGalleries;
        if($images){
            foreach ($images as $image) {
                $this->deleteImage($image->image);
                $image->delete();
            };
        }
        $product->delete();
        return response([
            "status" => "success",
            "message" => "Deleted Product Successfully",
            "is_empty" => isTableEmpty(Product::get())
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

    public function changeStatus(String $id)
    {
        $product = Product::findOrFail($id);
        $newStatus = $product->status == 0 ? 1 : 0;
        $product->update(["status" => $newStatus]);
        return response([
            "status" => "success",
            "message" => "Update Product Status Successfully",
        ]);
    }

    public function changeProductApproved(Request $req)
    {
        $product = Product::findOrFail($req->productID);
        $newStatus = $req->productStatus;
        $product->update([
            "is_approved" => $newStatus,
        ]);
        return response([
            "status" => "Update Product Status Successfully"
        ]);
    }
}
