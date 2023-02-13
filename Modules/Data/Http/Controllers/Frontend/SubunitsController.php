<?php

namespace Modules\Data\Http\Controllers\Frontend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Log;
use Auth;
use Modules\Data\Services\SubunitService;
use Spatie\Activitylog\Models\Activity;

class SubunitsController extends Controller
{
    protected $subunitService;

    public function __construct(SubunitService $subunitService)
    {
        // Page Title
        $this->module_title = trans('menu.data.subunits');

        // module name
        $this->module_name = 'subunits';

        // directory path of the module
        $this->module_path = 'subunits';

        // module icon
        $this->module_icon = 'fas fa-user-tie';

        // module model name, path
        $this->module_model = "Modules\Subunit\Entities\Subunit";

        $this->subunitService = $subunitService;
    }

    /**
     * Go to subunit homepage
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

        $subunits = $this->subunitService->getAllSubunits()->data;

        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "data::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "subunits",'driver')
        );
    }


    /**
     * Go to subunit catalog
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

        $subunits = $this->subunitService->getPaginatedSubunits(20,$request)->data;
        
        if ($request->ajax()) {
            return view("data::frontend.$module_name.subunits-card-loader", ['subunits' => $subunits])->render();  
        }
        
        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "data::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "subunits",'driver')
        );
    }

    /**
     * Go to subunit catalog
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function filterSubunits(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $subunits = $this->subunitService->filterSubunits(20,$request)->data;
        
        if ($request->ajax()) {
            return view("data::frontend.$module_name.subunits-card-loader", ['subunits' => $subunits])->render();  
        }
        
    }


    /**
     * Show subunit details
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function show($id,$subunitId)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $subunit = $this->subunitService->show($id)->data;
        
        
        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "data::frontend.$module_name.show",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "subunit",'driver')
        );
    }
}
