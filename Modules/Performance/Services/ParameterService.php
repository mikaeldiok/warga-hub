<?php

namespace Modules\Performance\Services;

use Modules\Performance\Entities\Core;
use Modules\Performance\Entities\Parameter;
use Modules\Recruiter\Entities\Booking;

use Exception;
use Carbon\Carbon;
use Auth;

use ConsoleTVs\Charts\Classes\Echarts\Chart;
use App\Exceptions\GeneralException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


use Modules\Performance\Imports\ParametersImport;
use Modules\Performance\Events\ParameterRegistered;

use App\Events\Backend\UserCreated;
use App\Events\Backend\UserUpdated;

use App\Models\User;
use App\Models\Userprofile;

class ParameterService{

    public function __construct()
        {        
        $this->module_title = Str::plural(class_basename(Parameter::class));
        $this->module_name = Str::lower($this->module_title);
        
        }

    public function list(){

        Log::info(label_case($this->module_title.' '.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        $parameter =Parameter::query()->orderBy('id','desc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $parameter,
        );
    }
    
    public function getAllParameters(){

        $parameter =Parameter::query()->available()->orderBy('id','desc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $parameter,
        );
    }

    public function filterParameters($pagination,$request){

        $parameter =Parameter::query()->available();

        if(count($request->all()) > 0){
            if($request->has('major')){
                $parameter->whereIn('major', $request->input('major'));
            }

        }

        $parameter = $parameter->paginate($pagination);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $parameter,
        );
    }

    public function getPaginatedParameters($pagination,$request){

        $parameter =Parameter::query()->available();

        if(count($request->all()) > 0){

        }

        $parameter = $parameter->paginate($pagination);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $parameter,
        );
    }
    
    public function get_parameter($request){

        $id = $request["id"];

        $parameter =Parameter::findOrFail($id);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $parameter,
        );
    }

    public function getList(){

        $parameter =Parameter::query()->orderBy('order','asc')->get();

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $parameter,
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
            
            $parameterObject = new Parameter;
            $parameterObject->fill($data);

            $parameterObjectArray = $parameterObject->toArray();

            $parameter = Parameter::create($parameterObjectArray);

            if ($request->hasFile('photo')) {
                if ($parameter->getMedia($this->module_name)->first()) {
                    $parameter->getMedia($this->module_name)->first()->delete();
                }
    
                $media = $parameter->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $parameter->photo = $media->getUrl();

                $parameter->save();
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

        Log::info(label_case($this->module_title.' '.__function__)." | '".$parameter->name.'(ID:'.$parameter->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $parameter,
        );
    }

    public function show($id, $parameterId = null){

        Log::info(label_case($this->module_title.' '.__function__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> Parameter::findOrFail($id),
        );
    }

    public function edit($id){

        $parameter = Parameter::findOrFail($id);

        Log::info(label_case($this->module_title.' '.__function__)." | '".$parameter->name.'(ID:'.$parameter->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $parameter,
        );
    }

    public function update(Request $request,$id){

        $data = $request->all();

        DB::beginTransaction();

        try{

            $parameter = new Parameter;
            $parameter->fill($data);
            
            $updating = Parameter::findOrFail($id)->update($parameter->toArray());

            $updated_parameter = Parameter::findOrFail($id);

            if ($request->hasFile('photo')) {
                if ($updated_parameter->getMedia($this->module_name)->first()) {
                    $updated_parameter->getMedia($this->module_name)->first()->delete();
                }
    
                $media = $updated_parameter->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $updated_parameter->photo = $media->getUrl();

                $updated_parameter->save();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$updated_parameter->name.'(ID:'.$updated_parameter->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $updated_parameter,
        );
    }

    public function destroy($id){

        DB::beginTransaction();

        try{
            $parameters = Parameter::findOrFail($id);
    
            $deleted = $parameters->delete();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$parameters->name.', ID:'.$parameters->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $parameters,
        );
    }

    public function trashed(){

        Log::info(label_case($this->module_title.' View'.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> Parameter::bookingonlyTrashed()->get(),
        );
    }

    public function restore($id){

        DB::beginTransaction();

        try{
            $restoring =  Parameter::bookingwithTrashed()->where('id',$id)->restore();
            $parameters = Parameter::findOrFail($id);
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

        Log::info(label_case(__FUNCTION__)." ".$this->module_title.": ".$parameters->name.", ID:".$parameters->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $parameters,
        );
    }

    public function purge($id){
        DB::beginTransaction();

        try{
            $parameters = Parameter::bookingwithTrashed()->findOrFail($id);
    
            $deleted = $parameters->forceDelete();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$parameters->name.', ID:'.$parameters->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $parameters,
        );
    }

    public function import(Request $request){
        $import = Excel::import(new ParametersImport($request), $request->file('data_file'));
    
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
        
        $opt = ["1","2","3","4","5","6","7","8","9"];
        $options = array(
            'opt'         => $opt,
        );

        return $options;
    }

}