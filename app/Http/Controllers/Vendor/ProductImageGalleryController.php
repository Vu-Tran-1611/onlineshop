<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\UploadTrait;
use App\Models\ProductImageGallery;
use App\Models\Product;
use App\DataTables\ProductImageGalleryDataTable;
class ProductImageGalleryController extends Controller
{
    use UploadTrait;
    public function index(ProductImageGalleryDataTable $dataTable, string $id)
    {
        $product = Product::findOrFail($id);
        return $dataTable->with("productID", $id)->render("vendor.product.image-gallery.index", compact("product"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request, string $id)
    {
        // Validate the request
        $request->validate([
            "images" => ["required", "array", "min:1"],
            "images.*" => ["required", "image", "mimes:jpeg,png,jpg,gif,svg,webp", "max:2048"],
        ], [
            "images.required" => "No image selected",
            "images.min" => "At least one image is required",
            "images.*.required" => "Each file must be a valid image",
            "images.*.image" => "Each file must be a valid image",
            "images.*.mimes" => "Images must be jpeg, png, jpg, gif, svg, or webp format",
            "images.*.max" => "Each image must not exceed 2MB"
        ]);

        // Verify the product exists
        $product = Product::findOrFail($id);

        try {
            // Upload images and create gallery entries
            $paths = $this->uploadMultiImage($request, "images", "uploads");

            $galleryEntries = [];
            foreach ($paths as $path) {
                // Extract filename from path for the name field
                $filename = basename($path);
                $name = pathinfo($filename, PATHINFO_FILENAME);

                $galleryEntries[] = [
                    "product_id" => $id,
                    "image" => $path,
                    "name" => $name,
                    "created_at" => now(),
                    "updated_at" => now()
                ];
            }

            // Bulk insert for better performance
            ProductImageGallery::insert($galleryEntries);

            return redirect()->back()->with('success', 'Images uploaded successfully!');

        } catch (\Exception $e) {
            // Clean up uploaded files if database insertion fails
            if (isset($paths)) {
                foreach ($paths as $path) {
                    $this->deleteImage($path);
                }
            }

            return redirect()->back()->with('error', 'Failed to upload images. Please try again.')->withInput();
        }
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $product_id, string $id)
    {
        try {
            $productImage = ProductImageGallery::findOrFail($id);

            // Verify the image belongs to the specified product
            if ($productImage->product_id != $product_id) {
                return response([
                    "status" => "error",
                    "message" => "Image does not belong to this product"
                ], 403);
            }

            // Delete the physical file
            $this->deleteImage($productImage->image);

            // Delete the database record
            $productImage->delete();

            return response([
                "status" => "success",
                "message" => "Image deleted successfully",
                "is_empty" => ProductImageGallery::where("product_id", $product_id)->count() === 0
            ]);

        } catch (\Exception $e) {
            return response([
                "status" => "error",
                "message" => "Failed to delete image. Please try again."
            ], 500);
        }
    }
    public function updateName(Request $request, string $id)
    {
        $request->validate([
            'input' => ['required', 'string', 'max:255']
        ]);

        try {
            $productImage = ProductImageGallery::findOrFail($id);
            $productImage->update(['name' => $request->input]);

            return response([
                "status" => "success",
                "message" => "Image name updated successfully",
            ]);

        } catch (\Exception $e) {
            return response([
                "status" => "error",
                "message" => "Failed to update image name. Please try again."
            ], 500);
        }
    }
}
