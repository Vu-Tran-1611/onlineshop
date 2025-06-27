<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\ShopProfile;
use Illuminate\Http\Request;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ShopProfileController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id = Auth::user()->id;
        $profile = ShopProfile::where("user_id", $id)->first();
        return view("vendor.shop-profile.index", compact("profile"));
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
        $request->validate([
            "name" => ["required"],
            "banner" => ["nullable"],
            "email" => ["email"],
        ]);

        $vendor = ShopProfile::where("user_id", Auth::user()->id)->first();
        $banner = $this->updateImage($request, $vendor, "uploads", "banner");
        $newBanner = empty($banner) ? $vendor->banner : $banner;

        $vendor->update([
            "name" => $request->name,
            "slug" => Str::slug($request->name),
            "banner" => $newBanner,
            "email" => $request->email,
            "phone" => $request->phone,
            "address" => $request->address,
            "description" => $request->description,
            "fb_link" => $request->fb_link,
            "tw_link" => $request->tw_link,
            "insta_link" => $request->insta_link,
        ]);
        Session::flash("status", "Update Shop Profile successfully");
        return redirect()->back();
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
