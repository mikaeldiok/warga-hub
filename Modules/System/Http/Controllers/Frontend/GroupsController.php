<?php

namespace Modules\System\Http\Controllers\Frontend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Log;
use Auth;
use Modules\System\Services\GroupService;
use Spatie\Activitylog\Models\Activity;

class GroupsController extends Controller
{
    protected $groupservice;

    public function __construct(GroupService $groupservice)
    {
        // Page Title
        $this->module_title = trans('menu.system.group');

        // module name
        $this->module_name = 'groups';

        // directory path of the module
        $this->module_path = 'groups';

        // module icon
        $this->module_icon = 'fas fa-user-tie';

        // module model name, path
        $this->module_model = "Modules\Group\Entities\Group";

        $this->groupservice = $groupservice;
    }

    /**
     * Go to group homepage
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

        $group = $this->groupservice->getAllGroup()->data;

        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "system::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "group",'driver')
        );
    }


    /**
     * Go to group catalog
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

        $group = $this->groupservice->getPaginatedGroup(20,$request)->data;
        
        if ($request->ajax()) {
            return view("system::frontend.$module_name.group-card-loader", ['group' => $group])->render();  
        }
        
        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "system::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "group",'driver')
        );
    }

    /**
     * Go to group catalog
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function filterGroup(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $group = $this->groupservice->filterGroup(20,$request)->data;
        
        if ($request->ajax()) {
            return view("system::frontend.$module_name.group-card-loader", ['group' => $group])->render();  
        }
        
    }


    /**
     * Show group details
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function show($id,$groupId)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $group = $this->groupservice->show($id)->data;
        
        
        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "system::frontend.$module_name.show",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "group",'driver')
        );
    }
}
