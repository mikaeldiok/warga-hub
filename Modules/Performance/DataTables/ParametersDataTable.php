<?php

namespace Modules\Performance\DataTables;

use Carbon\Carbon;
use Illuminate\Support\HtmlString;
use Modules\Performance\Services\ParameterService;
use Modules\Performance\Entities\Parameter;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ParametersDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function __construct(ParameterService $parameterService)
    {
        $this->module_name = 'parameters';

        $this->parameterService = $parameterService;
    }

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($data) {
                $module_name = $this->module_name;

                return view('backend.includes.action_column', compact('module_name', 'data'));
            })
            ->editColumn('available', function ($data) {
                $module_name = $this->module_name;

                if($data->available)
                {
                    $availability = '<p class="text-white text-center bg-success rounded">YES</p>';
                }else{
                    $availability = '<p class="text-white text-center bg-danger rounded">NO</p>';
                }

                return $availability;
            })
            ->editColumn('photo', function ($data) {
                $module_name = $this->module_name;

                $pictureView = '<img src="'.asset($data->photo).'" class="user-profile-image img-fluid img-thumbnail" style="max-height:150px; max-width:100px;" />';

                return $pictureView;
            })
            ->editColumn('updated_at', function ($data) {
                $module_name = $this->module_name;

                $diff = Carbon::now()->diffInHours($data->updated_at);

                if ($diff < 25) {
                    return $data->updated_at->diffForHumans();
                } else {
                    return $data->updated_at->isoFormat('LLLL');
                }
            })
            ->editColumn('created_at', function ($data) {
                $module_name = $this->module_name;

                $formated_date = Carbon::parse($data->created_at)->format('d-m-Y, H:i:s');

                return $formated_date;
            })
            ->rawColumns(['name', 'action','photo','available']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Parameter $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $user = auth()->user();
        $data = Parameter::with('unit');

        return $this->applyScopes($data);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $created_at = 2;
        return $this->builder()
                ->setTableId('parameters-table')
                ->columns($this->getColumns())
                ->minifiedAjax()
                ->dom(config('mk-datatables.mk-dom'))
                ->orderBy($created_at,'desc')
                ->buttons(
                    Button::make('export'),
                    Button::make('print'),
                    Button::make('reset')->className('rounded-right'),
                    Button::make('colvis')->text('Kolom')->className('m-2 rounded btn-info'),
                )->parameters([
                    'paging' => true,
                    'searching' => true,
                    'info' => true,
                    'responsive' => true,
                    'autoWidth' => false,
                    'searchDelay' => 350,
                ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->addClass('text-center'),
            Column::make('id')->hidden(),
            Column::make('date')->title(__("performance::parameters.date")),
            Column::make('unit.name')->title(__("data::units.name")),
            Column::make('available')->title(__("performance::parameters.available")),
            Column::make('created_at'),
            Column::make('updated_at')->hidden(),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Parameters_' . date('YmdHis');
    }
}