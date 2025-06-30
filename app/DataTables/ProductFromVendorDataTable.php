<?php

namespace App\DataTables;

use App\Models\ProductFromVendor;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Models\Product;

class ProductFromVendorDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('isApproved', function ($query) {
                $approveStatus = [
                    "-1" => "Pending",
                    "0" => "Rejected",
                    "1" => "Approved",
                ];
                $html = "";
                foreach ($approveStatus as $key => $value) {
                    if ($key == $query->is_approved)
                        $option = "<option data-id={$query->id} selected value='$key'  > $value </option>";
                    else
                        $option = "<option data-id={$query->id} value='$key' > $value </option>";
                    $html .= $option;
                };
                return "<div class=' form-group'>
                    <select data-id='$query->id' class='product_approved form-control'>
                        " . $html . "
                    </select> 
                </div>";
            })
            ->addColumn('status', function ($query) {
                if ($query->status == 1) {
                    return
                        '<label class="custom-switch mt-2">
                    <input type="checkbox" checked data-url=" ' . route("admin.product.change_status", $query->id) . '" class="status custom-switch-input">
                    <span class="custom-switch-indicator"></span>
                </label>';
                } else {
                    return
                        '<label class="custom-switch mt-2">
                    <input type="checkbox" data-url=" ' . route("admin.product.change_status", $query->id) . '"  class="status custom-switch-input">
                    <span class="custom-switch-indicator"></span>
                </label>';
                }
            })
            ->addColumn("image", function ($query) {

                $img = " <img width=100 src='" . asset($query->thumb_image) . " '/> ";
                return $img;
            })
            ->addColumn('action', function ($query) {
                $updateBtn = "<a href = '" . route("admin.product.edit", $query->id) . " ' class='btn btn-primary'><i class='fa-solid fa-pen-to-square'></i> </a> &emsp;";
                $deleteBtn = "<button class='delete btn btn-danger' data-url='" . route("admin.product.destroy", $query->id) . "'><i class='fa-solid fa-trash-can-arrow-up'></i></button>";
                $moreBtn = ' <div class="ml-2 dropleft d-inline ">
            <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa-solid fa-gear"></i>

            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item has-icon" href="' . route("admin.product.image-gallery.index", $query->id) . '"><i class="far fa-heart"></i> Image Gallery</a>
              <a class="dropdown-item has-icon" href="' . route("admin.product.variant.index", $query->id) . '"><i class="far fa-file"></i>Variants</a>
            </div>
          </div>';
                return $updateBtn . $deleteBtn . $moreBtn;
            })
            ->addColumn('product_type', function ($query) {
                $types = [
                    "none" => "None",
                    "top" => "Top Product",
                    "best" => "Best Product",
                    "featured" => "Featured Product",
                    "new_arrival" => "New Arrival",
                ];
                $typesHTML = "";
                foreach ($types as $key => $value) {
                    if ($key == $query->product_type) $typesHTML .= "<option selected value='$key'> $value </option>";
                    else $typesHTML .= "<option  value='$key'> $value </option>";
                };
                return "<div class=' form-group'><select data-id='$query->id' class='product_type form-control'>" . $typesHTML . "</select> </div>";
            })
            ->addColumn('shopName', function ($query) {
                return $query->shopProfile->name;
            })
            ->rawColumns(["product_type", "image", "action", "status", "isApproved"])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('id', 'desc');
    }
    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('productfromvendor-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [

            Column::make('id'),
            Column::make("image"),
            Column::make("shopName"),
            Column::make("name"),
            Column::make("price"),
            Column::make("product_type")->width(150),
            Column::make("isApproved"),
            Column::make("status"),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ProductFromVendor_' . date('YmdHis');
    }
}
