<?php

namespace Modules\Performance\Http\Controllers\Frontend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Log;
use Auth;
use Modules\Performance\Services\ParameterService;
use Spatie\Activitylog\Models\Activity;

class ParametersController extends Controller
{
    protected $parameterService;

    public function __construct(ParameterService $parameterService)
    {
        // Page Title
        $this->module_title = trans('menu.performance.parameters');

        // module name
        $this->module_name = 'parameters';

        // directory path of the module
        $this->module_path = 'parameters';

        // module icon
        $this->module_icon = 'fas fa-user-tie';

        // module model name, path
        $this->module_model = "Modules\Parameter\Entities\Parameter";

        $this->parameterService = $parameterService;
    }

    /**
     * Go to parameter homepage
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $datetime = $request->input('datetime');
        $parameters = $this->parameterService->getAllParameters($request)->data;

        return view(
            "performance::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "parameters", "datetime")
        );
    }


    /**
     * Go to parameter catalog
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

        $parameters = $this->parameterService->getPaginatedParameters(20,$request)->data;
        
        if ($request->ajax()) {
            return view("performance::frontend.$module_name.parameters-card-loader", ['parameters' => $parameters])->render();  
        }
        
        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "performance::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "parameters",'driver')
        );
    }

    /**
     * Go to parameter catalog
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function filterParameters(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $parameters = $this->parameterService->filterParameters(20,$request)->data;
        
        if ($request->ajax()) {
            return view("performance::frontend.$module_name.parameters-card-loader", ['parameters' => $parameters])->render();  
        }
        
    }


    /**
     * Show parameter details
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function show($id,$parameterId)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $parameter = $this->parameterService->show($id)->data;
        
        
        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "performance::frontend.$module_name.show",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "parameter",'driver')
        );
    }
}
