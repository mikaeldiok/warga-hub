<?php

namespace Modules\System\Http\Controllers\Frontend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Log;
use Auth;
use Modules\System\Services\AppsiteService;
use Spatie\Activitylog\Models\Activity;

class AppsitesController extends Controller
{
    protected $appsiteservice;

    public function __construct(AppsiteService $appsiteservice)
    {
        // Page Title
        $this->module_title = trans('menu.system.appsite');

        // module name
        $this->module_name = 'appsites';

        // directory path of the module
        $this->module_path = 'appsites';

        // module icon
        $this->module_icon = 'fas fa-user-tie';

        // module model name, path
        $this->module_model = "Modules\Appsite\Entities\Appsite";

        $this->appsiteservice = $appsiteservice;
    }

    /**
     * Go to appsite homepage
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

        $appsite = $this->appsiteservice->getAllAppsite()->data;

        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "system::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "appsite",'driver')
        );
    }


    /**
     * Go to appsite catalog
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

        $appsite = $this->appsiteservice->getPaginatedAppsite(20,$request)->data;
        
        if ($request->ajax()) {
            return view("system::frontend.$module_name.appsite-card-loader", ['appsite' => $appsite])->render();  
        }
        
        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "system::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "appsite",'driver')
        );
    }

    /**
     * Go to appsite catalog
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function filterAppsite(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $appsite = $this->appsiteservice->filterAppsite(20,$request)->data;
        
        if ($request->ajax()) {
            return view("system::frontend.$module_name.appsite-card-loader", ['appsite' => $appsite])->render();  
        }
        
    }


    /**
     * Show appsite details
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function show($id,$appsiteId)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $appsite = $this->appsiteservice->show($id)->data;
        
        
        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "system::frontend.$module_name.show",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "appsite",'driver')
        );
    }
}
