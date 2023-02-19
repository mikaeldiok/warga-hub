<?php

namespace Modules\Data\Http\Controllers\Frontend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Log;
use Auth;
use Modules\Data\Services\FeeService;
use Spatie\Activitylog\Models\Activity;

class FeesController extends Controller
{
    protected $feeService;

    public function __construct(FeeService $feeService)
    {
        // Page Title
        $this->module_title = trans('menu.data.fees');

        // module name
        $this->module_name = 'fees';

        // directory path of the module
        $this->module_path = 'fees';

        // module icon
        $this->module_icon = 'fas fa-user-tie';

        // module model name, path
        $this->module_model = "Modules\Fee\Entities\Fee";

        $this->feeService = $feeService;
    }

    /**
     * Go to fee homepage
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

        $fees = $this->feeService->getAllFees()->data;

        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "data::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "fees",'driver')
        );
    }


    /**
     * Go to fee catalog
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

        $fees = $this->feeService->getPaginatedFees(20,$request)->data;
        
        if ($request->ajax()) {
            return view("data::frontend.$module_name.fees-card-loader", ['fees' => $fees])->render();  
        }
        
        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "data::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "fees",'driver')
        );
    }

    /**
     * Go to fee catalog
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function filterFees(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $fees = $this->feeService->filterFees(20,$request)->data;
        
        if ($request->ajax()) {
            return view("data::frontend.$module_name.fees-card-loader", ['fees' => $fees])->render();  
        }
        
    }


    /**
     * Show fee details
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function show($id,$feeId)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $fee = $this->feeService->show($id)->data;
        
        
        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "data::frontend.$module_name.show",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "fee",'driver')
        );
    }
}
