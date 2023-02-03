<?php

namespace Modules\System\Services;

use Modules\System\Entities\Core;
use Modules\System\Entities\Appsite;
use Modules\Recruiter\Entities\Booking;

use Exception;
use Carbon\Carbon;
use Auth;

use ConsoleTVs\Charts\Classes\Echarts\Chart;
use App\Charts\AppsitePerStatus;
use App\Exceptions\GeneralException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


use Modules\System\Imports\AppsiteImport;
use Modules\System\Events\AppsiteRegistered;

use App\Events\Backend\UserCreated;
use App\Events\Backend\UserUpdated;

use App\Models\User;
use App\Models\Userprofile;

class AppsiteService{

    public function __construct()
        {        
        $this->module_title = Str::plural(class_basename(Appsite::class));
        $this->module_name = Str::lower($this->module_title);
        
        }

    public function list(){

        Log::info(label_case($this->module_title.' '.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        $appsite =Appsite::query()->orderBy('id','desc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $appsite,
        );
    }
    
    public function getAllAppsite(){

        $appsite =Appsite::query()->available()->orderBy('id','desc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $appsite,
        );
    }

    public function filterAppsite($pagination,$request){

        $appsite =Appsite::query()->available();

        if(count($request->all()) > 0){
            if($request->has('major')){
                $appsite->whereIn('major', $request->input('major'));
            }

        }

        $appsite = $appsite->paginate($pagination);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $appsite,
        );
    }

    public function getPaginatedAppsite($pagination,$request){

        $appsite =Appsite::query()->available();

        if(count($request->all()) > 0){

        }

        $appsite = $appsite->paginate($pagination);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $appsite,
        );
    }
    
    public function get_appsite($request){

        $id = $request["id"];

        $appsite =Appsite::findOrFail($id);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $appsite,
        );
    }

    public function getList(){

        $appsite =Appsite::query()->orderBy('order','asc')->get();

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $appsite,
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
            
            $appsiteObject = new Appsite;
            $appsiteObject->fill($data);

            $appsiteObjectArray = $appsiteObject->toArray();

            $appsite = Appsite::create($appsiteObjectArray);

            if ($request->hasFile('photo')) {
                if ($appsite->getMedia($this->module_name)->first()) {
                    $appsite->getMedia($this->module_name)->first()->delete();
                }
    
                $media = $appsite->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $appsite->photo = $media->getUrl();

                $appsite->save();
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

        Log::info(label_case($this->module_title.' '.__function__)." | '".$appsite->name.'(ID:'.$appsite->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $appsite,
        );
    }

    public function show($id, $appsiteId = null){

        Log::info(label_case($this->module_title.' '.__function__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> Appsite::findOrFail($id),
        );
    }

    public function edit($id){

        $appsite = Appsite::findOrFail($id);

        Log::info(label_case($this->module_title.' '.__function__)." | '".$appsite->name.'(ID:'.$appsite->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $appsite,
        );
    }

    public function update(Request $request,$id){

        $data = $request->all();

        DB::beginTransaction();

        try{

            $appsite = new Appsite;
            $appsite->fill($data);
            
            $updating = Appsite::findOrFail($id)->update($appsite->toArray());

            $updated_appsite = Appsite::findOrFail($id);

            if ($request->hasFile('photo')) {
                if ($updated_appsite->getMedia($this->module_name)->first()) {
                    $updated_appsite->getMedia($this->module_name)->first()->delete();
                }
    
                $media = $updated_appsite->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $updated_appsite->photo = $media->getUrl();

                $updated_appsite->save();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$updated_appsite->name.'(ID:'.$updated_appsite->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $updated_appsite,
        );
    }

    public function destroy($id){

        DB::beginTransaction();

        try{
            $appsite = Appsite::findOrFail($id);
    
            $deleted = $appsite->delete();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$appsite->name.', ID:'.$appsite->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $appsite,
        );
    }

    public function trashed(){

        Log::info(label_case($this->module_title.' View'.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> Appsite::bookingonlyTrashed()->get(),
        );
    }

    public function restore($id){

        DB::beginTransaction();

        try{
            $restoring =  Appsite::bookingwithTrashed()->where('id',$id)->restore();
            $appsite = Appsite::findOrFail($id);
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

        Log::info(label_case(__FUNCTION__)." ".$this->module_title.": ".$appsite->name.", ID:".$appsite->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $appsite,
        );
    }

    public function purge($id){
        DB::beginTransaction();

        try{
            $appsite = Appsite::bookingwithTrashed()->findOrFail($id);
    
            $deleted = $appsite->forceDelete();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$appsite->name.', ID:'.$appsite->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $appsite,
        );
    }

    public function import(Request $request){
        $import = Excel::import(new AppsiteImport($request), $request->file('data_file'));
    
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