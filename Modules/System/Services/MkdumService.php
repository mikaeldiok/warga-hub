<?php

namespace Modules\Mkstarter\Services;

use Modules\Mkstarter\Entities\Core;
use Modules\Mkstarter\Entities\Mkdum;
use Modules\Recruiter\Entities\Booking;

use Exception;
use Carbon\Carbon;
use Auth;

use ConsoleTVs\Charts\Classes\Echarts\Chart;
use App\Charts\MkdumPerStatus;
use App\Exceptions\GeneralException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


use Modules\Mkstarter\Imports\MkdumsImport;
use Modules\Mkstarter\Events\MkdumRegistered;

use App\Events\Backend\UserCreated;
use App\Events\Backend\UserUpdated;

use App\Models\User;
use App\Models\Userprofile;

class MkdumService{

    public function __construct()
        {        
        $this->module_title = Str::plural(class_basename(Mkdum::class));
        $this->module_name = Str::lower($this->module_title);
        
        }

    public function list(){

        Log::info(label_case($this->module_title.' '.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        $mkdum =Mkdum::query()->orderBy('id','desc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $mkdum,
        );
    }
    
    public function getAllMkdums(){

        $mkdum =Mkdum::query()->available()->orderBy('id','desc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $mkdum,
        );
    }

    public function filterMkdums($pagination,$request){

        $mkdum =Mkdum::query()->available();

        if(count($request->all()) > 0){
            if($request->has('major')){
                $mkdum->whereIn('major', $request->input('major'));
            }

        }

        $mkdum = $mkdum->paginate($pagination);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $mkdum,
        );
    }

    public function getPaginatedMkdums($pagination,$request){

        $mkdum =Mkdum::query()->available();

        if(count($request->all()) > 0){

        }

        $mkdum = $mkdum->paginate($pagination);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $mkdum,
        );
    }
    
    public function get_mkdum($request){

        $id = $request["id"];

        $mkdum =Mkdum::findOrFail($id);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $mkdum,
        );
    }

    public function getList(){

        $mkdum =Mkdum::query()->orderBy('order','asc')->get();

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $mkdum,
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
            
            $mkdumObject = new Mkdum;
            $mkdumObject->fill($data);

            $mkdumObjectArray = $mkdumObject->toArray();

            $mkdum = Mkdum::create($mkdumObjectArray);

            if ($request->hasFile('photo')) {
                if ($mkdum->getMedia($this->module_name)->first()) {
                    $mkdum->getMedia($this->module_name)->first()->delete();
                }
    
                $media = $mkdum->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $mkdum->photo = $media->getUrl();

                $mkdum->save();
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

        Log::info(label_case($this->module_title.' '.__function__)." | '".$mkdum->name.'(ID:'.$mkdum->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $mkdum,
        );
    }

    public function show($id, $mkdumId = null){

        Log::info(label_case($this->module_title.' '.__function__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> Mkdum::findOrFail($id),
        );
    }

    public function edit($id){

        $mkdum = Mkdum::findOrFail($id);

        Log::info(label_case($this->module_title.' '.__function__)." | '".$mkdum->name.'(ID:'.$mkdum->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $mkdum,
        );
    }

    public function update(Request $request,$id){

        $data = $request->all();

        DB::beginTransaction();

        try{

            $mkdum = new Mkdum;
            $mkdum->fill($data);
            
            $updating = Mkdum::findOrFail($id)->update($mkdum->toArray());

            $updated_mkdum = Mkdum::findOrFail($id);

            if ($request->hasFile('photo')) {
                if ($updated_mkdum->getMedia($this->module_name)->first()) {
                    $updated_mkdum->getMedia($this->module_name)->first()->delete();
                }
    
                $media = $updated_mkdum->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $updated_mkdum->photo = $media->getUrl();

                $updated_mkdum->save();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$updated_mkdum->name.'(ID:'.$updated_mkdum->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $updated_mkdum,
        );
    }

    public function destroy($id){

        DB::beginTransaction();

        try{
            $mkdums = Mkdum::findOrFail($id);
    
            $deleted = $mkdums->delete();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$mkdums->name.', ID:'.$mkdums->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $mkdums,
        );
    }

    public function trashed(){

        Log::info(label_case($this->module_title.' View'.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> Mkdum::bookingonlyTrashed()->get(),
        );
    }

    public function restore($id){

        DB::beginTransaction();

        try{
            $restoring =  Mkdum::bookingwithTrashed()->where('id',$id)->restore();
            $mkdums = Mkdum::findOrFail($id);
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

        Log::info(label_case(__FUNCTION__)." ".$this->module_title.": ".$mkdums->name.", ID:".$mkdums->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $mkdums,
        );
    }

    public function purge($id){
        DB::beginTransaction();

        try{
            $mkdums = Mkdum::bookingwithTrashed()->findOrFail($id);
    
            $deleted = $mkdums->forceDelete();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$mkdums->name.', ID:'.$mkdums->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $mkdums,
        );
    }

    public function import(Request $request){
        $import = Excel::import(new MkdumsImport($request), $request->file('data_file'));
    
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