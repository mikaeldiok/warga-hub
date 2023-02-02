<?php

namespace Modules\School\Services;

use Modules\School\Entities\Core;
use Modules\School\Entities\Student;
use Modules\Recruiter\Entities\Booking;

use Exception;
use Carbon\Carbon;
use Auth;

use ConsoleTVs\Charts\Classes\Echarts\Chart;
use App\Charts\StudentPerStatus;
use App\Exceptions\GeneralException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


use Modules\School\Imports\StudentsImport;
use Modules\School\Events\StudentRegistered;

use App\Events\Backend\UserCreated;
use App\Events\Backend\UserUpdated;

use App\Models\User;
use App\Models\Userprofile;

class StudentService{

    public function __construct()
        {        
        $this->module_title = Str::plural(class_basename(Student::class));
        $this->module_name = Str::lower($this->module_title);
        
        }

    public function list(){

        Log::info(label_case($this->module_title.' '.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        $student =Student::query()->orderBy('id','desc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $student,
        );
    }
    
    public function getAllStudents(){

        $student =Student::query()->available()->orderBy('id','desc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $student,
        );
    }

    public function getPopularStudent(){

        $student =DB::table('bookings')
                    ->select('bookings.student_id','name','', DB::raw('count(*) as total'))
                    ->join('students', 'bookings.student_id', '=', 'students.id')
                    ->groupBy('bookings.student_id')
                    ->orderBy('total','desc')
                    ->get();
                
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $student,
        );
    }

    public function filterStudents($pagination,$request){

        $student =Student::query()->available();

        if(count($request->all()) > 0){
            if($request->has('major')){
                $student->whereIn('major', $request->input('major'));
            }

            if($request->has('year_class')){
                $student->whereIn('year_class', $request->input('year_class'));
            }

            if($request->has('height')){
                $student->where('height', ">=", (float)$request->input('height'));
            }

            if($request->has('weight')){
                $student->where('weight', ">=", (float)$request->input('weight'));
            }

            if($request->has('skills')){
                $student->where(function ($query) use ($request){
                    $checkSkills = $request->input('skills');
                    foreach($checkSkills as $skill){
                        if($request->input('must_have_all_skills')){
                            $query->where('skills', 'like','%'.$skill.'%');
                        }else{
                            $query->orWhere('skills', 'like','%'.$skill.'%');
                        }
                    }
                });
            }

            if($request->has('certificate')){
                $student->where(function ($query) use ($request){
                    $checkCerts = $request->input('certificate');
                    foreach($checkCerts as $cert){
                        if($request->input('must_have_all_certificate')){
                            $query->where('certificate', 'like','%'.$cert.'%');
                        }else{
                            $query->orWhere('certificate', 'like','%'.$cert.'%');
                        }
                    }
                });
            }
        }

        $student = $student->paginate($pagination);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $student,
        );
    }

    public function getPaginatedStudents($pagination,$request){

        $student =Student::query()->available();

        if(count($request->all()) > 0){

        }

        $student = $student->paginate($pagination);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $student,
        );
    }
    
    public function get_student($request){

        $id = $request["id"];

        $student =Student::findOrFail($id);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $student,
        );
    }

    public function getList(){

        $student =Student::query()->orderBy('order','asc')->get();

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $student,
        );
    }


    public function create(){

       Log::info(label_case($this->module_title.' '.__function__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? '0').')');
        
        $createOptions = $this->prepareOptions();

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $createOptions,
        );
    }

    public function store(Request $request){

        $data = $request->all();
        DB::beginTransaction();

        try {
            
            $studentObject = new Student;
            $studentObject->fill($data);

            if($studentObject->birth_date){
                $studentObject->birth_date = Carbon::createFromFormat('d/m/Y', $studentObject->birth_date)->format('Y-m-d'); 
            }

            if($studentObject->skills){
                $studentObject->skills = implode(',', $studentObject->skills); 
            }

            if($studentObject->certificate){
                $studentObject->certificate = implode(',', $studentObject->certificate); 
            }

            $studentObjectArray = $studentObject->toArray();

            $student = Student::create($studentObjectArray);

            if ($request->hasFile('photo')) {
                if ($student->getMedia($this->module_name)->first()) {
                    $student->getMedia($this->module_name)->first()->delete();
                }
    
                $media = $student->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $student->photo = $media->getUrl();

                $student->save();
            }
            
        }catch (Exception $e){
            DB::rollBack();
            Log::critical(label_case($this->module_title.' ON LINE '.__LINE__.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        Log::info(label_case($this->module_title.' '.__function__)." | '".$student->name.'(ID:'.$student->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $student,
        );
    }

    public function show($id, $studentId = null){

        Log::info(label_case($this->module_title.' '.__function__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> Student::findOrFail($id),
        );
    }

    public function edit($id){

        $student = Student::findOrFail($id);

        if($student->skills){
            $student->skills = explode(',', $student->skills); 
        }

        if($student->certificate){
            $student->certificate = explode(',', $student->certificate); 
        }
        
        Log::info(label_case($this->module_title.' '.__function__)." | '".$student->name.'(ID:'.$student->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $student,
        );
    }

    public function update(Request $request,$id){

        $data = $request->all();

        DB::beginTransaction();

        try{

            $student = new Student;
            $student->fill($data);
            
            if($student->birth_date){
                $student->birth_date = Carbon::createFromFormat('d/m/Y', $student->birth_date)->format('Y-m-d'); 
            }

            if($student->skills){
                $student->skills = implode(',', $student->skills); 
            }

            if($student->certificate){
                $student->certificate = implode(',', $student->certificate); 
            }
            
            $updating = Student::findOrFail($id)->update($student->toArray());

            $updated_student = Student::findOrFail($id);

            if ($request->hasFile('photo')) {
                if ($updated_student->getMedia($this->module_name)->first()) {
                    $updated_student->getMedia($this->module_name)->first()->delete();
                }
    
                $media = $updated_student->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $updated_student->photo = $media->getUrl();

                $updated_student->save();
            }


        }catch (Exception $e){
            DB::rollBack();
            report($e);
            Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | Msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$updated_student->name.'(ID:'.$updated_student->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $updated_student,
        );
    }

    public function destroy($id){

        DB::beginTransaction();

        try{
            $students = Student::findOrFail($id);
    
            $deleted = $students->delete();
        }catch (Exception $e){
            DB::rollBack();
            Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | Msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$students->name.', ID:'.$students->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $students,
        );
    }

    public function trashed(){

        Log::info(label_case($this->module_title.' View'.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> Student::bookingonlyTrashed()->get(),
        );
    }

    public function restore($id){

        DB::beginTransaction();

        try{
            $restoring =  Student::bookingwithTrashed()->where('id',$id)->restore();
            $students = Student::findOrFail($id);
        }catch (Exception $e){
            DB::rollBack();
            Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | Msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        Log::info(label_case(__FUNCTION__)." ".$this->module_title.": ".$students->name.", ID:".$students->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $students,
        );
    }

    public function purge($id){
        DB::beginTransaction();

        try{
            $students = Student::bookingwithTrashed()->findOrFail($id);
    
            $deleted = $students->forceDelete();
        }catch (Exception $e){
            DB::rollBack();
            Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | Msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$students->name.', ID:'.$students->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $students,
        );
    }

    public function import(Request $request){
        $import = Excel::import(new StudentsImport($request), $request->file('data_file'));
    
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $import,
        );
    }

    public static function prepareStatusFilter(){
        
        $raw_status = Core::getRawData('recruitment_status');
        $status = [];
        foreach($raw_status as $key => $value){
            $status += [$value => $value];
        }

        return $status;
    }

    public static function prepareOptions(){
        
        $raw_majors = Core::getRawData('major');
        $majors = [];
        foreach($raw_majors as $key => $value){
            $majors += [$value => $value];
        }

        $skills_raw = Core::getRawData('skills');
        $skills = [];
        foreach($skills_raw as $value){
            $skills += [$value => $value];
        }

        $certificate_raw= Core::getRawData('certificate');
        $certificate = [];
        foreach($certificate_raw as $value){
            $certificate += [$value => $value];
        }

        $options = array(
            'majors'         => $majors,
            'skills'              => $skills,
            'certificate'         => $certificate,
        );

        return $options;
    }

    public static function prepareFilter(){
        
        $options = self::prepareOptions();

        $year_class_raw = DB::table('students')
                        ->select('year_class', DB::raw('count(*) as total'))
                        ->groupBy('year_class')
                        ->orderBy('year_class','desc')
                        ->get();
        $year_class = [];
            foreach($year_class_raw as $item){
                $year_class += [$item->year_class => $item->year_class];
                // $year_class += [$item->year_class => $item->year_class." (".$item->total.")"];
            }


        $filterOp = array(
            'year_class'          => $year_class,
        );

        return array_merge($options,$filterOp);
    }

    public function getStudentPerStatusChart(){

        $chart = new Chart;

        $raw_status_order = Core::getRawData('recruitment_status');
        $status_order = [];
        foreach($raw_status_order as $key => $value){
            $status_order += [$value => 0];
        }

        $last_key = array_key_last($status_order);
        $remove_last_status = array_pop($status_order);

        $raw_majors = Core::getRawData('major');
        $majors = [];

        foreach($raw_majors as $key => $value){
            $majors[] = $value;
        }

        foreach($majors as $major){

            $status_raw = DB::table('bookings')
                        ->select('status', DB::raw('count(*) as total'))
                        ->join('students', 'bookings.student_id', '=', 'students.id')
                        ->where('students.major',$major)
                        ->where('students.available',1)
                        ->where('status',"<>",$last_key)
                        ->groupBy('status')
                        ->orderBy('status','desc')
                        ->get();
            $status = [];

            foreach($status_raw as $item){
                $status += [$item->status => $item->total];
            }

            $status = array_merge($status_order, $status);

            [$keys, $values] = Arr::divide($status);

            $chart->labels($keys);

            $chart->dataset($major, 'bar',$values);
        }

        $chart->options([
            "xAxis" => [
                "axisLabel" => [
                    "interval" => 0,
                    "overflow" => "truncate",
                ],
            ],
            "yAxis" => [
                "minInterval" => 1
            ],
        ]);

        return $chart;
    }

    public function getDoneStudentsChart(){

        $chart = new Chart;

        $raw_status_order = Core::getRawData('recruitment_status');
        $status_order = [];
        foreach($raw_status_order as $key => $value){
            $status_order += [$value => 0];
        }

        $last_key = array_key_last($status_order);
        $remove_last_status = array_pop($status_order);

        $raw_majors = Core::getRawData('major');
        $majors = [];

        foreach($raw_majors as $key => $value){
            $majors[] = $value;
        }

        $year_class_list_raw = DB::table('students')
                                ->select('year_class')
                                ->groupBy('year_class')
                                ->orderBy('year_class','asc')
                                ->limit(8)
                                ->get();
        
        $year_class_list= [];


        foreach($year_class_list_raw as $item){
            $year_class_list += [$item->year_class => 0];
        }                    

        foreach($majors as $major){

            $year_class_raw = DB::table('bookings')
                        ->select('students.year_class', DB::raw('count(*) as total'))
                        ->join('students', 'bookings.student_id', '=', 'students.id')
                        ->distinct()
                        ->where('students.major',$major)
                        ->where('status',"=",$last_key)
                        ->groupBy('students.year_class')
                        ->orderBy('students.year_class','asc')
                        ->get();

            $year_class = [];

            foreach($year_class_raw as $item){
                $year_class += [$item->year_class => $item->total];
            }

            $year_class =  $year_class + $year_class_list;

            ksort($year_class);

            [$keys, $values] = Arr::divide($year_class);

            $chart->labels($keys);

            $chart->dataset($major, 'bar',$values);
        }

        $chart->options([
            "xAxis" => [
                "axisLabel" => [
                    "interval" => 0,
                    "overflow" => "truncate",
                ],
            ],
            "yAxis" => [
                "minInterval" => 1
            ],
        ]);

        return $chart;
    }

    public function getStudentPerYearClassChart(){

        $chart = new Chart;

        $students_active = DB::table('students')
                            ->select('year_class', DB::raw('count(*) as total'))
                            ->where('available',1)
                            ->groupBy('year_class')
                            ->orderBy('year_class','asc')
                            ->get();

        $students=[];
        foreach($students_active as $item){
            $students += [$item->year_class => $item->total];
        }

        [$keys, $values] = Arr::divide($students);

        $chart->labels($keys);

        $chart->dataset("Jumlah Siswa", 'bar',$values);
        
        $chart->options([
            "xAxis" => [
                "axisLabel" => [
                    "interval" => 0,
                    "overflow" => "truncate",
                ],
            ],
            "yAxis" => [
                "minInterval" => 1
            ],
        ]);

        return $chart;
    }

    public static function prepareInsight(){

        $countAllStudents = Student::all()->count();

        $raw_status= Core::getRawData('recruitment_status');
        $status = [];

        foreach($raw_status as $key => $value){
            $status[] = $value;
        }

        $countDoneStudents = Booking::where('status',end($status))->get()->count();
        
        $stats = (object) array(
            'status'                    => $status,
            'countAllStudents'          => $countAllStudents,
            'countDoneStudents'         => $countDoneStudents,
        );

        return $stats;
    }

}