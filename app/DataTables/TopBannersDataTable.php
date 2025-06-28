<?php

namespace App\DataTables;

use App\Models\TopBanner;
use App\Models\TopBanners;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TopBannersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))

            ->addColumn('start_date', function ($query) {
                if ($query->start_date) {
                    return Carbon::parse($query->start_date)->format('d-m-Y');
                }
                return '';
            })
            ->addColumn('end_date', function ($query) {
                if ($query->end_date) {
                    return Carbon::parse($query->end_date)->format('d-m-Y');
                }
                return '';
            })
            ->addColumn('status', function ($query) {
                if ($query->is_active == 1) {
                    return
                        '<label class="custom-switch mt-2">
                        <input type="checkbox" checked data-url=" ' . route("admin.top-banner.change-status", $query->id) . '" class="status custom-switch-input">
                        <span class="custom-switch-indicator"></span>
                    </label>';
                } else {
                    return
                        '<label class="custom-switch mt-2">
                        <input type="checkbox" data-url=" ' . route("admin.top-banner.change-status", $query->id) . '"  class="status custom-switch-input">
                        <span class="custom-switch-indicator"></span>
                    </label>';
                }
            })
            ->addColumn('action', function ($query) {
                $updateBtn = "<a href = '" . route("admin.top-banner.edit", $query->id) . " ' class='btn btn-primary'><i class='fa-solid fa-pen-to-square'></i> </a> &emsp;";
                $deleteBtn = "<button class='delete btn btn-danger' data-url='" . route("admin.top-banner.destroy", $query->id) . "'><i class='fa-solid fa-trash-can-arrow-up'></i></button>";
                return $updateBtn . $deleteBtn;
            })
            ->rawColumns(["action", "status"])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(TopBanners $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('topbanners-table')
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
            Column::make('label'),
            Column::make('text'),
            Column::make('start_date'),
            Column::make('end_date'),
            Column::computed('status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'TopBanners_' . date('YmdHis');
    }
}
