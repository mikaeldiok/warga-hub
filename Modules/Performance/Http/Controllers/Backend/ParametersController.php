<?php

namespace Modules\Performance\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Log;
use Modules\Performance\Services\ParameterService;
use Modules\Performance\DataTables\ParametersDataTable;
use Modules\Performance\Http\Requests\Backend\ParametersRequest;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;

class ParametersController extends Controller
{
    use Authorizable;

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
        $this->module_icon = 'fas fa-graduation-cap';

        // module model name, path
        $this->module_model = "Modules\Performance\Entities\Parameter";

        $this->parameterService = $parameterService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(ParametersDataTable $dataTable)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';

        $$module_name = $module_model::paginate();

        return $dataTable->render("performance::backend.$module_path.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Create';

        $options = $this->parameterService->create()->data;
        
        return view(
            "performance::backend.$module_name.create",
            compact('module_title', 'module_name', 'module_icon', 'module_action', 'module_name_singular','options')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(ParametersRequest $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Store';

        $parameters = $this->parameterService->store($request);

        $$module_name_singular = $parameters->data;

        if(!$parameters->error){
            Flash::success('<i class="fas fa-check"></i> '.label_case($module_name_singular).' Data Added Successfully!')->important();
        }else{
            Flash::error("<i class='fas fa-times-circle'></i> Error When ".$module_action." '".Str::singular($module_title)."'")->important();
        }

        return redirect("admin/$module_name");
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Show';

        $parameters = $this->parameterService->show($id);

        $$module_name_singular = $parameters->data;

        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        $activities = Activity::where('subject_type', '=', $module_model)
            ->where('log_name', '=', $module_name)
            ->where('subject_id', '=', $id)
            ->latest()
            ->paginate();

        return view(
            "performance::backend.$module_name.show",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "$module_name_singular",'activities','driver')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Edit';

        $parameters = $this->parameterService->edit($id);

        $$module_name_singular = $parameters->data;

        $options = $this->parameterService->prepareOptions();
        
        return view(
            "performance::backend.$module_name.edit",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "$module_name_singular",'options')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function update(ParametersRequest $request, $id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Update';

        $this->validate($request, [
            'photo'    => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        $parameters = $this->parameterService->update($request,$id);

        $$module_name_singular = $parameters->data;

        if(!$parameters->error){
            Flash::success('<i class="fas fa-check"></i> '.label_case($module_name_singular).' Data Updated Successfully!')->important();
        }else{
            Flash::error("<i class='fas fa-times-circle'></i> Error When ".$module_action." '".Str::singular($module_title)."'")->important();
        }

        return redirect("admin/$module_name");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'destroy';

        $parameters = $this->parameterService->destroy($id);

        $$module_name_singular = $parameters->data;

        if(!$parameters->error){
            Flash::success('<i class="fas fa-check"></i> '.label_case($module_name_singular).' Data Deleted Successfully!')->important();
        }else{
            Flash::error("<i class='fas fa-times-circle'></i> Error When ".$module_action." '".Str::singular($module_title)."'")->important();
        }

        return redirect("admin/$module_name");
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function purge($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'purge';

        $parameters = $this->parameterService->purge($id);

        $$module_name_singular = $parameters->data;

        if(!$parameters->error){
            Flash::success('<i class="fas fa-check"></i> '.label_case($module_name_singular).' Data Deleted Successfully!')->important();
        }else{
            Flash::error("<i class='fas fa-times-circle'></i> Error When ".$module_action." '".Str::singular($module_title)."'")->important();
        }

        return redirect("admin/$module_name/trashed");
    }

    /**
     * List of trashed ertries
     * works if the softdelete is enabled.
     *
     * @return Response
     */
    public function trashed()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Trash List';

        $parameters = $this->parameterService->trashed();

        $$module_name = $parameters->data;

        return view(
            "performance::backend.$module_name.trash",
            compact('module_title', 'module_name', "$module_name", 'module_icon', 'module_name_singular', 'module_action')
        );
    }

    /**
     * Restore a soft deleted entry.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function restore($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Restore';

        $parameters = $this->parameterService->restore($id);

        $$module_name_singular = $parameters->data;

        if(!$parameters->error){
            Flash::success('<i class="fas fa-check"></i> '.label_case($module_name_singular).' Data Restored Successfully!')->important();
        }else{
            Flash::error("<i class='fas fa-times-circle'></i> Error When ".$module_action." '".Str::singular($module_title)."'")->important();
        }
        
        return redirect("admin/$module_name");
    }

    /**
     * Restore a soft deleted entry.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function import(Request $request)
    {

        $this->validate($request,[
            'data_file' => 'mimes:csv,xls,xlsx',
            'photo_file' => 'mimes:zip,rar,7z'
         ]);

        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Import';
        
        $parameters = $this->parameterService->import($request);

        $import = $parameters->data;

        if(!$parameters->error){
            Flash::success('<i class="fas fa-check"></i> '.label_case($module_name_singular).' Data Restored Successfully!')->important();
        }else{
            Flash::error("<i class='fas fa-times-circle'></i> Error When ".$module_action." '".Str::singular($module_title)."'")->important();
        }
        
        return redirect("admin/$module_name");
    }

    /**
     * FOr getting parameter data via ajax
     *
     * @return Response
     */
    public function get_parameter(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';

        $response = $this->parameterService->get_parameter($request);

        return response()->json($response);
    }
}
