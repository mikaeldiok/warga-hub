<?php

namespace Modules\Mkstarter\Http\Controllers\Frontend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Log;
use Auth;
use Modules\Mkstarter\Services\MkdumService;
use Spatie\Activitylog\Models\Activity;

class MkdumsController extends Controller
{
    protected $mkdumService;

    public function __construct(MkdumService $mkdumService)
    {
        // Page Title
        $this->module_title = trans('menu.mkstarter.mkdums');

        // module name
        $this->module_name = 'mkdums';

        // directory path of the module
        $this->module_path = 'mkdums';

        // module icon
        $this->module_icon = 'fas fa-user-tie';

        // module model name, path
        $this->module_model = "Modules\Mkdum\Entities\Mkdum";

        $this->mkdumService = $mkdumService;
    }

    /**
     * Go to mkdum homepage
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

        $mkdums = $this->mkdumService->getAllMkdums()->data;

        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "mkstarter::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "mkdums",'driver')
        );
    }


    /**
     * Go to mkdum catalog
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

        $mkdums = $this->mkdumService->getPaginatedMkdums(20,$request)->data;
        
        if ($request->ajax()) {
            return view("mkstarter::frontend.$module_name.mkdums-card-loader", ['mkdums' => $mkdums])->render();  
        }
        
        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "mkstarter::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "mkdums",'driver')
        );
    }

    /**
     * Go to mkdum catalog
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function filterMkdums(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $mkdums = $this->mkdumService->filterMkdums(20,$request)->data;
        
        if ($request->ajax()) {
            return view("mkstarter::frontend.$module_name.mkdums-card-loader", ['mkdums' => $mkdums])->render();  
        }
        
    }


    /**
     * Show mkdum details
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function show($id,$mkdumId)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $mkdum = $this->mkdumService->show($id)->data;
        
        
        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "mkstarter::frontend.$module_name.show",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "mkdum",'driver')
        );
    }
}
