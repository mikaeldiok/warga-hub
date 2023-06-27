<?php

namespace Modules\Performance\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Concerns\FromView;
use Modules\Performance\Entities\Parameter;


class ParametersExport implements FromView
{
    public function view(): View
    {
        $parameters =Parameter::join('units', 'parameters.unit_id', '=', 'units.id')
                    ->orderBy('units.sequence', 'asc')
                    ->select('parameters.*')
                    ->with('unit');

        $datetime = request()->input('datetime');

        if(isset($datetime)){
            $trueDate = \Carbon\Carbon::createFromFormat('M/Y', $datetime);

            $response = $parameters->whereYear('parameters.date', '=', $trueDate->year)
                                    ->whereMonth('parameters.date', '=', $trueDate->month)
                                    ->get();
        }else{
            $response = $parameters->where('parameters.available', true)->get();
        }

        return view('performance::frontend.parameters.table', [
            'parameters' => $response,
            'param_points' => Config::get('performance.parameters'),
        ]);
    }
}