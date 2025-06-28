<?php

namespace App\Http\Controllers\admin;

use App\DataTables\TopBannerDataTable;
use App\DataTables\TopBannersDataTable;
use App\Http\Controllers\Controller;
use App\Models\TopBanners;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TopBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TopBannersDataTable $dataTable)
    {
        return $dataTable->render('admin.topbanner.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.topbanner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required',
            'text' => 'required',
            'link' => 'required|url',
            'is_active' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        TopBanners::create($request->all());
        Session::flash('status', 'Top Banner created successfully');
        return redirect()->route('admin.top-banner.index');
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
        $topBanner = TopBanners::findOrFail($id);
        return view('admin.topbanner.edit', compact('topBanner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'label' => 'required',
            'text' => 'required',
            'link' => 'required|url',
            'is_active' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $topBanner = TopBanners::findOrFail($id);
        $topBanner->update($request->all());
        Session::flash("status", "Top Banner updated successfully");
        return redirect()->route('admin.top-banner.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $topBanner = TopBanners::findOrFail($id);
        $topBanner->delete();
        return response(["status" => "success", "message" => "Deleted Top Banner", "is_empty" => isTableEmpty(TopBanners::get())]);
    }


    /**
     * Change the status of the top banner.
     */
    public function changeStatus(Request $request, string $id)
    {
        $topBanner = TopBanners::findOrFail($id);
        $newStatus = $topBanner->is_active == 0 ? 1 : 0;
        $topBanner->update([
            "is_active" => $newStatus,
        ]);
        return response([
            "status" => "success",
            "message" => "Updated Top Banner Status"
        ]);
    }
}
