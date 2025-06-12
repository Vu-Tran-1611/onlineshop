<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserAddresses;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    use UploadTrait;
    public function index()
    {
        $title = "Profile";
        $user = Auth::user();
        $categories = Category::get();

        return view(
            "frontend.pages.profile-account",
            compact("title", "user", "categories")
        );
    }
    // Update Profile
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if ($request->image) {
            $request->validate([
                "image" => ["image"],
            ]);
            $image = $this->updateImage($request, $user->image, "uploads", "image");
            $user->update([
                "image" => $image,
            ]);
        } else {

            $request->validate([
                "username" => ["required"],
                "name" => ["required"],
                "email" => ["required", "unique:users,email," . $user->id],
                "phone" => ["required"],
            ]);
            $user->update([
                "username" => $request->username,
                "email" => $request->email,
                "phone" => $request->phone,
                "name" => $request->name,
            ]);
        };
        return redirect()->back();
    }

    // Update Password 
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            "current_password" => ["required", "current_password"],
            "password" => [
                "required",
                "confirmed",
                "string",
                "min:8",
                'regex:/^(?=.*[A-Z])(?=.*\d).+$/',
            ],
        ]);
        $user->update(["password" => bcrypt($request->password)]);
        Session::flash("status", "Update Password Successfully");
        return redirect()->back();
    }
}
