<?php

namespace Modules\Data\Services;

use Modules\Data\Entities\Core;
use Modules\Data\Entities\Unit;
use Modules\Recruiter\Entities\Booking;

use Exception;
use Carbon\Carbon;
use Auth;

use ConsoleTVs\Charts\Classes\Echarts\Chart;
use App\Charts\UnitPerStatus;
use App\Exceptions\GeneralException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


use Modules\Data\Imports\UnitsImport;
use Modules\Data\Events\UnitRegistered;

use App\Events\Backend\UserCreated;
use App\Events\Backend\UserUpdated;

use App\Models\User;
use App\Models\Userprofile;

class UnitService{

    public function __construct()
        {        
        $this->module_title = Str::plural(class_basename(Unit::class));
        $this->module_name = Str::lower($this->module_title);
        
        }

    public function list(){

        Log::info(label_case($this->module_title.' '.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        $unit =Unit::query()->orderBy('id','desc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $unit,
        );
    }
    
    public function getAllUnits(){

        $unit =Unit::query()->available()->orderBy('id','desc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $unit,
        );
    }

    public function filterUnits($pagination,$request){

        $unit =Unit::query()->available();

        if(count($request->all()) > 0){
            if($request->has('major')){
                $unit->whereIn('major', $request->input('major'));
            }

        }

        $unit = $unit->paginate($pagination);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $unit,
        );
    }

    public function getPaginatedUnits($pagination,$request){

        $unit =Unit::query()->available();

        if(count($request->all()) > 0){

        }

        $unit = $unit->paginate($pagination);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $unit,
        );
    }
    
    public function get_unit($request){

        $id = $request["id"];

        $unit =Unit::findOrFail($id);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $unit,
        );
    }

    public function getList(){

        $unit =Unit::query()->orderBy('order','asc')->get();

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $unit,
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
            
            $unitObject = new Unit;
            $unitObject->fill($data);

            $unitObjectArray = $unitObject->toArray();

            $unit = Unit::create($unitObjectArray);

            if ($request->hasFile('photo')) {
                if ($unit->getMedia($this->module_name)->first()) {
                    $unit->getMedia($this->module_name)->first()->delete();
                }
    
                $media = $unit->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $unit->photo = $media->getUrl();

                $unit->save();
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

        Log::info(label_case($this->module_title.' '.__function__)." | '".$unit->name.'(ID:'.$unit->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $unit,
        );
    }

    public function show($id, $unitId = null){

        Log::info(label_case($this->module_title.' '.__function__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> Unit::findOrFail($id),
        );
    }

    public function edit($id){

        $unit = Unit::findOrFail($id);

        Log::info(label_case($this->module_title.' '.__function__)." | '".$unit->name.'(ID:'.$unit->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $unit,
        );
    }

    public function update(Request $request,$id){

        $data = $request->all();

        DB::beginTransaction();

        try{

            $unit = new Unit;
            $unit->fill($data);
            
            $updating = Unit::findOrFail($id)->update($unit->toArray());

            $updated_unit = Unit::findOrFail($id);

            if ($request->hasFile('photo')) {
                if ($updated_unit->getMedia($this->module_name)->first()) {
                    $updated_unit->getMedia($this->module_name)->first()->delete();
                }
    
                $media = $updated_unit->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $updated_unit->photo = $media->getUrl();

                $updated_unit->save();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$updated_unit->name.'(ID:'.$updated_unit->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $updated_unit,
        );
    }

    public function destroy($id){

        DB::beginTransaction();

        try{
            $units = Unit::findOrFail($id);
    
            $deleted = $units->delete();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$units->name.', ID:'.$units->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $units,
        );
    }

    public function trashed(){

        Log::info(label_case($this->module_title.' View'.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> Unit::bookingonlyTrashed()->get(),
        );
    }

    public function restore($id){

        DB::beginTransaction();

        try{
            $restoring =  Unit::bookingwithTrashed()->where('id',$id)->restore();
            $units = Unit::findOrFail($id);
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

        Log::info(label_case(__FUNCTION__)." ".$this->module_title.": ".$units->name.", ID:".$units->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $units,
        );
    }

    public function purge($id){
        DB::beginTransaction();

        try{
            $units = Unit::bookingwithTrashed()->findOrFail($id);
    
            $deleted = $units->forceDelete();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$units->name.', ID:'.$units->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $units,
        );
    }

    public function import(Request $request){
        $import = Excel::import(new UnitsImport($request), $request->file('data_file'));
    
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