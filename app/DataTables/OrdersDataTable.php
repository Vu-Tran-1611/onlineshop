<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OrdersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))

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

            ->rawColumns(["action", "status", "isApproved"])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Order $model): QueryBuilder
    {
        return Order::query()
            ->select('orders.*')
            ->with(['user', 'orderItems.product'])
            ->where('vendor_id', $this->vendorId)
            ->when($this->orderType !== 'all', function ($query) {
                return $query->where('status', $this->orderType);
            });
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('orders-table')
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
            Column::make("status"),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-center'),
            Column::make('id'),
            Column::make('created_at'),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Orders_' . date('YmdHis');
    }
}
