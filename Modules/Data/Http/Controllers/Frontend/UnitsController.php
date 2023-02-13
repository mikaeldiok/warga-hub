<?php

namespace Modules\Data\Http\Controllers\Frontend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Log;
use Auth;
use Modules\Data\Services\UnitService;
use Spatie\Activitylog\Models\Activity;

class UnitsController extends Controller
{
    protected $unitService;

    public function __construct(UnitService $unitService)
    {
        // Page Title
        $this->module_title = trans('menu.data.units');

        // module name
        $this->module_name = 'units';

        // directory path of the module
        $this->module_path = 'units';

        // module icon
        $this->module_icon = 'fas fa-user-tie';

        // module model name, path
        $this->module_model = "Modules\Unit\Entities\Unit";

        $this->unitService = $unitService;
    }

    /**
     * Go to unit homepage
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function index()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $units = $this->unitService->getAllUnits()->data;

        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "data::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "units",'driver')
        );
    }


    /**
     * Go to unit catalog
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function indexPaginated(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $units = $this->unitService->getPaginatedUnits(20,$request)->data;
        
        if ($request->ajax()) {
            return view("data::frontend.$module_name.units-card-loader", ['units' => $units])->render();  
        }
        
        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "data::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "units",'driver')
        );
    }

    /**
     * Go to unit catalog
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function filterUnits(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $units = $this->unitService->filterUnits(20,$request)->data;
        
        if ($request->ajax()) {
            return view("data::frontend.$module_name.units-card-loader", ['units' => $units])->render();  
        }
        
    }


    /**
     * Show unit details
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function show($id,$unitId)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $unit = $this->unitService->show($id)->data;
        
        
        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "data::frontend.$module_name.show",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "unit",'driver')
        );
    }
}
