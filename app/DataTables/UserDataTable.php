<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('role', function ($query) {
                if($query->role == "admin") {
                    return '<span class="badge badge-primary">Admin</span>';
                } elseif($query->role == "vendor") {
                    return '<span class="badge badge-success">Vendor</span>';
                } else {
                    return '<span class="badge badge-secondary">User</span>';
                }
            })
            ->addColumn('action', function ($query) {
                if ($query->status == "active") {
                    return
                        '<label class="custom-switch mt-2">
                        <input type="checkbox" checked data-url=" ' . route("admin.user.change-status", $query->id) . '" class="status custom-switch-input">
                        <span class="custom-switch-indicator"></span>
                    </label>';
                } else {
                    return
                        '<label class="custom-switch mt-2">
                        <input type="checkbox" data-url=" ' . route("admin.user.change-status", $query->id) . '"  class="status custom-switch-input">
                        <span class="custom-switch-indicator"></span>
                    </label>';
                }
            })

            ->rawColumns(["action","role"])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()
            ->orderByRaw("CASE
                WHEN role = 'admin' THEN 1
                WHEN role = 'vendor' THEN 2
                WHEN role = 'user' THEN 3
                ELSE 4
            END");
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('user-table')
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
            Column::make('role'),
            Column::make('email'),
            Column::make('created_at'),
            Column::make('updated_at'),
            Column::make('action')->title("Active/Inactive"),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'User_' . date('YmdHis');
    }
}
