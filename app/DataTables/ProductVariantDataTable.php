<?php

namespace App\DataTables;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;
class ProductVariantDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        //
        $role = Auth::user()->role;
        return (new EloquentDataTable($query))
            ->addColumn('status', function ($query) use ($role) {
                if ($query->status == 1) {
                    return
                        '<label class="custom-switch mt-2">
                        <input type="checkbox" checked data-url=" ' . route("$role.product.variant.change_status", $query->id) . '" class="status custom-switch-input">
                        <span class="custom-switch-indicator"></span>
                    </label>';
                } else {
                    return
                        '<label class="custom-switch mt-2">
                        <input type="checkbox" data-url=" ' . route("$role.product.variant.change_status", $query->id) . '"  class="status custom-switch-input">
                        <span class="custom-switch-indicator"></span>
                    </label>';
                }
            })
            ->addColumn('is_swipe', function ($query) use ($role) {
                if ($query->is_swipe == 1) {
                    return
                        '<label class="custom-switch mt-2">
                        <input type="checkbox" checked data-url=" ' . route("$role.product.variant.change_is_swipe", $query->id) . '" class="is_swipe status custom-switch-input">
                        <span class="custom-switch-indicator"></span>
                    </label>';
                } else {
                    return
                        '<label class="custom-switch mt-2">
                        <input type="checkbox" data-url=" ' . route("$role.product.variant.change_is_swipe", $query->id) . '"  class="is_swipe status custom-switch-input">
                        <span class="custom-switch-indicator"></span>
                    </label>';
                }
            })
            ->addColumn('action', function ($query) use ($role){
                $moreBtn = "<a href = '" . route("$role.product.variant.item.index", [$query->product_id, $query->id]) . " ' class='ml-3 btn btn-primary'><i class='fa-solid fa-list'></i> </a> &emsp;";
                $updateBtn = "<a href = '" . route("$role.product.variant.edit", [$query->product_id, $query->id]) . " ' class='btn btn-primary'><i class='fa-solid fa-pen-to-square'></i> </a> &emsp;";
                $deleteBtn = "<button class='delete btn btn-danger' data-url='". route("$role.product.variant.destroy",  [$query->product_id, $query->id]) ."'><i class='fa-solid fa-trash-can-arrow-up'></i></button>";
                return  $moreBtn.$updateBtn . $deleteBtn  ;
            })
            ->rawColumns(["action", "status","is_swipe"])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ProductVariant $model): QueryBuilder
    {
        return ProductVariant::where("product_id", $this->id);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('productvariant-table')
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
            Column::make('id')->width(50),
            Column::make('name'),
            Column::computed('status'),
            Column::computed('is_swipe'),
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
        return 'ProductVariant_' . date('YmdHis');
    }
}
