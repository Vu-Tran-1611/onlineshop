<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserAddresses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Address";
        $addresses = UserAddresses::where("user_id", Auth::user()->id)
            ->orderByDesc("is_default")
            ->orderBy("created_at", 'desc')
            ->paginate(4);
        $categories = \App\Models\Category::all();
        return view("frontend.pages.profile-address", compact("title", "addresses", "categories")); // Pass to view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->is_default) {
            foreach (UserAddresses::where('user_id', Auth::user()->id)->get() as $addr) {
                if ($addr->is_default == 1) $addr->update(["is_default" => false]);
            }
        }
        $request->validate([
            "name" => ["required", "string"],
            "phone" => ["required", 'string'],
            "country" => ["required", 'string'],
            "state" => ["required", 'string'],
            "city" => ["required", 'string'],
            "zip" => ["required", 'string'],
            "address" => ["required", 'string'],
            "type" => ["required", 'string']
        ]);
        $address = UserAddresses::create([
            "user_id" => Auth::user()->id,
            "name" => $request->name,
            "phone" => $request->phone,
            "country" => $request->country,
            "state" => $request->state,
            "city" => $request->city,
            "zip" => $request->zip,
            "address" => $request->address,
            "type" => $request->type,
            "is_default" => $request->is_default ? true : false,
        ]);
        return response([
            "status" => "success",
            "message" => "Added New Address",
            "address" => $address,
            "deleteURL" => route("user.address.destroy", $address->id),
            "updateURL" => route("user.address.update", $address->id),
            "setDefaultURL" => route("user.address.set-default", $address->id),
            "is_default" =>  $request->is_default ? true : false,
        ]);
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
        if ($request->is_default) {
            foreach (UserAddresses::where('user_id', Auth::user()->id)->get() as $addr) {
                if ($addr->is_default == 1) $addr->update(["is_default" => false]);
            }
        }
        $request->validate([
            "name" => ["required", "string"],
            "phone" => ["required", 'string'],
            "country" => ["required", 'string'],
            "state" => ["required", 'string'],
            "city" => ["required", 'string'],
            "zip" => ["required", 'string'],
            "address" => ["required", 'string'],
            "type" => ["required", 'string']
        ]);
        $addr = UserAddresses::findOrFail($id);
        $addr->update([
            "name" => $request->name,
            "phone" => $request->phone,
            "country" => $request->country,
            "state" => $request->state,
            "city" => $request->city,
            "zip" => $request->zip,
            "address" => $request->address,
            "type" => $request->type,
            "is_default" => $request->is_default ? true : false,
        ]);
        return response([
            "status" => "success",
            "message" => "Updated Address",
            "address" => $addr,
            "is_default" =>  $request->is_default ? true : false,
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $addr = UserAddresses::findOrFail($id);
        $addr->delete();
        return response([
            "status" => 'success',
            "message" => "Delete Address Successfully",

        ]);
    }

    public function setDefault(string $id)
    {
        foreach (UserAddresses::where('user_id', Auth::user()->id)->get() as $addr) {
            if ($addr->is_default == 1) $addr->update(["is_default" => false]);
        }
        $addr = UserAddresses::findOrFail($id);
        $addr->update(["is_default" => true]);
        return response([
            "status" => 'success',
            "message" => "Set Default Successfully",
            "url" => route("user.address.set-default", $id),
            "id" =>   $addr->id,
        ]);
    }

    public function get(Request $request)
    {
        $addr = UserAddresses::findOrFail($request->id);
        return response([
            "address" => $addr,
            "status" => "success",
        ]);
    }
}
