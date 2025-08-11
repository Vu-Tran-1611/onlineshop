<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BrandDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\UploadTrait;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
class BrandController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(BrandDatatable $dataTable)
    {
        return $dataTable->render("admin.brand.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();
        return view("admin.brand.create",compact("categories"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => ["required", "unique:brands,name"],
            "logo" => ["image", "required"],
            "status" => ["required"],
            "is_featured" => ["required"],
        ]);

        $path = $this->uploadImage($request, "uploads", "logo");

        $brand = Brand::create([
            "slug" => Str::slug($request->name),
            "name" => $request->name,
            "logo" => $path,
            "status" => $request->status,
            "is_featured" => $request->is_featured,
        ]);
        foreach($request->category_id as $id){
            $brand->categories()->attach($id);
        }
        Session::flash("status", "Create brand successfully");
        return redirect()->route("admin.brand.index");
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $brand = Brand::findOrFail($id);
        $categories = Category::get();

        return view("admin.brand.edit", [
            "brand" => $brand,
            "categories" => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $brand = Brand::findOrFail($id);
        $request->validate([
            "name" => [ "unique:brands,name," . $id],
            "logo" => ["image"],
        ]);
        $newPath = $this->updateImage($request, $brand->logo, "uploads", "logo");
        $path = $newPath ?? $brand->logo;

        $brand->update([
            "name" => $request->name,
            "logo" => $path,
            "status" => $request->status,
            "is_featured" => $request->is_featured,
        ]);
        $brand->categories()->sync($request->category_id);
        Session::flash("status", "Update Brand successfully");
        return redirect()->route("admin.brand.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        $this->deleteImage($brand->logo);
        return response(["status" => "success","message" => "Deleted Brand","is_empty" => isTableEmpty(Brand::get())]);

    }

    public function changeStatus(string $id)
    {
        $brand = Brand::findOrFail($id);
        $newStatus = $brand->status == 0 ? 1 : 0;
        $brand->update([
            "status" => $newStatus,
        ]);
        return response([
            "status" => "success","message" => "Updated Brand Status"
        ]);
    }
    public function changeFeatured(string $id)
    {
        $brand = Brand::findOrFail($id);
        $newFeatured = $brand->is_featured == 0 ? 1 : 0;
        $brand->update([
            "is_featured" => $newFeatured,
        ]);
        return response([
            "status" => "success","message" => "Updated Brand Featured"
        ]);
    }

    public function getCategory(string $id){
        $brand = Brand::with("categories")->findOrFail($id);
        $categoryIDs = array();
        foreach($brand->categories as $s){
            $categoryIDs[] = $s->id;
        };
        return response($categoryIDs);
    }
}
