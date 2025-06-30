<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
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
                if ($query->is_approved == -1) {
                    return
                        '<span class="badge bg-warning text-white">Pending</span>';
                } else if ($query->is_approved == 0)
                    return
                        '<span class="badge bg-danger text-white">Rejected</span>';
                else return '<span class="badge bg-success text-white">Success</span>';
            })
            ->addColumn('status', function ($query) {
                if ($query->status == 1) {
                    return
                        '<label class="custom-switch mt-2">
                    <input type="checkbox" checked data-url=" ' . route("vendor.product.change_status", $query->id) . '" class="status custom-switch-input">
                    <span class="custom-switch-indicator"></span>
                </label>';
                } else {
                    return
                        '<label class="custom-switch mt-2">
                    <input type="checkbox" data-url=" ' . route("vendor.product.change_status", $query->id) . '"  class="status custom-switch-input">
                    <span class="custom-switch-indicator"></span>
                </label>';
                }
            })
            ->addColumn("image", function ($query) {

                $img = " <img width=100 src='" . asset($query->thumb_image) . " '/> ";
                return $img;
            })
            ->addColumn('action', function ($query) {
                $updateBtn = "<a href = '" . route("vendor.product.edit", $query->id) . " ' class='ml-3 btn btn-primary'><i class='fa-solid fa-pen-to-square'></i> </a>";
                $deleteBtn = "<a href = '" . route("vendor.product.destroy", $query->id) . " ' class='ml-3 btn btn-danger delete-item'><i class='fa-solid fa-trash'></i> </a>";
                $moreBtn = ' <div class="ml-2 dropleft d-inline ">
                            <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa-solid fa-gear"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item has-icon" href="' . route("vendor.product.image-gallery.index", $query->id) . '"><i class="far fa-heart"></i> Image Gallery</a>
                                <a class="dropdown-item has-icon" href="' . route("vendor.product.variant.index", $query->id) . '"><i class="far fa-file"></i>Variants</a>
                            </div>
                            </div>';
                return $moreBtn . $updateBtn . $deleteBtn;
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
            ->rawColumns(["product_type", "image", "action", "status", "isApproved"])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->where("shop_profile_id", auth()->user()->shop_profile->id)->orderBy('id', 'desc');
    }


    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('product-table')
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
            Column::make("name"),
            Column::make("price"),
            Column::make("product_type")->width(150),
            Column::make("status"),
            Column::make("isApproved"),
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
        return 'Product_' . date('YmdHis');
    }
}
