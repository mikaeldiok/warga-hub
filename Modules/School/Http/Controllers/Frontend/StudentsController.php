<?php

namespace Modules\School\Http\Controllers\Frontend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Log;
use Auth;
use Modules\School\Services\StudentService;
use Spatie\Activitylog\Models\Activity;

class StudentsController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        // Page Title
        $this->module_title = trans('menu.school.students');

        // module name
        $this->module_name = 'students';

        // directory path of the module
        $this->module_path = 'students';

        // module icon
        $this->module_icon = 'fas fa-user-tie';

        // module model name, path
        $this->module_model = "Modules\Student\Entities\Student";

        $this->studentService = $studentService;
    }

    /**
     * Go to student homepage
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

        $students = $this->studentService->getAllStudents()->data;

        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "school::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "students",'driver')
        );
    }


    /**
     * Go to student catalog
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

        $students = $this->studentService->getPaginatedStudents(20,$request)->data;
        
        if ($request->ajax()) {
            return view("school::frontend.$module_name.students-card-loader", ['students' => $students])->render();  
        }
        
        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "school::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "students",'driver')
        );
    }

    /**
     * Go to student catalog
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function filterStudents(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $students = $this->studentService->filterStudents(20,$request)->data;
        
        if ($request->ajax()) {
            return view("school::frontend.$module_name.students-card-loader", ['students' => $students])->render();  
        }
        
    }


    /**
     * Show student details
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function show($id,$studentId)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $student = $this->studentService->show($id)->data;
        
        
        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "school::frontend.$module_name.show",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "student",'driver')
        );
    }
}
