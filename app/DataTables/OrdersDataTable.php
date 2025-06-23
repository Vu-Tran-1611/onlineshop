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


            ->addColumn('action', function ($query) {
                $updateBtn = "<a href = '" . route("vendor.orders.show", $query->id) . " ' class='ml-3 btn btn-primary'><i class='fa-solid fa-pen-to-square'></i> </a>";
                return $updateBtn;
            })
            ->addColumn("order_time", function ($query) {
                return $query->created_at->format("d M Y h:i A");
            })
            ->addColumn("total", function ($query) {
                return $query->orderProductTotalByVendor($this->vendorId);
            })
            ->addColumn("product_qty", function ($query) {
                return $query->orderProductsByVendor($this->vendorId)->sum("qty");
            })

            ->rawColumns(["action"])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Order $model): QueryBuilder
    {

        return $model::whereHas("orderProducts", function ($query) {
            return $query->where("vendor_id", $this->vendorId);
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
            Column::make('invoice_id'),
            Column::make("product_qty"),
            Column::make("total"),
            Column::make('order_time'),
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
        return 'Orders_' . date('YmdHis');
    }
}
