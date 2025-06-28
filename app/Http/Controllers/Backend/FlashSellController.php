<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlashSellItem;
use App\DataTables\FlashSellItemDataTable;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use App\Models\FlashSell;

class FlashSellController extends Controller
{
    public function index(FlashSellItemDataTable $dataTable)
    {
        $productsFromSaleItems = FlashSellItem::get("product_id");
        $endDate = FlashSell::first("end_date");
        $products = Product::where("status", 1)
            ->where("is_approved", 1)
            ->whereNotIn("id", $productsFromSaleItems)
            ->get();
        return $dataTable->render("admin.flash-sell.index", [
            "products" => $products,
            "endDate" => $endDate->end_date,
        ]);
    }
    public function store(Request $req)
    {
        $req->validate([
            "product_id" => "required",
            "show_at_home" => "required",
        ]);
        FlashSellItem::create([
            "product_id" => $req->product_id,
            "show_at_home" => $req->show_at_home,
        ]);
        Session::flash("status", "Store flash sale product successfully");
        return redirect()->back();
    }
    public function update(Request $req)
    {
        $req->validate(["end_date" => "required"]);
        FlashSell::updateOrCreate(
            ["id" => 1],
            ["end_date" => $req->end_date],
        );
        Session::flash("status", "Update flash sale time successfully");

        return redirect()->back();
    }

    public function changeStatus(string $id)
    {
        $item = FlashSellItem::findOrFail($id);
        $newStatus = $item->show_at_home == 0 ? 1 : 0;
        $item->update([
            "show_at_home" => $newStatus,
        ]);
        return response([
            "status" => "success",
            "message" => "Update Product Status Successfully"
        ]);
    }
    public function destroy(string $id)
    {
        $item = FlashSellItem::findOrFail($id);
        $item->delete();
        return response([
            "status" => "success",
            "message" => "Deleted Product Successfully",
            "is_empty" => isTableEmpty(FlashSellItem::get()),
        ]);
    }
}
