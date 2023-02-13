<?php

namespace Modules\Data\Services;

use Modules\Data\Entities\Unit;
use Modules\Data\Entities\Subunit;
use Modules\Recruiter\Entities\Booking;

use Exception;
use Carbon\Carbon;
use Auth;

use ConsoleTVs\Charts\Classes\Echarts\Chart;
use App\Charts\SubunitPerStatus;
use App\Exceptions\GeneralException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


use Modules\Data\Imports\SubunitsImport;
use Modules\Data\Events\SubunitRegistered;

use App\Events\Backend\UserCreated;
use App\Events\Backend\UserUpdated;

use App\Models\User;
use App\Models\Userprofile;

class SubunitService{

    public function __construct()
        {        
        $this->module_title = Str::plural(class_basename(Subunit::class));
        $this->module_name = Str::lower($this->module_title);
        
        }

    public function list(){

        Log::info(label_case($this->module_title.' '.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        $subunit =Subunit::query()->orderBy('id','desc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $subunit,
        );
    }
    
    public function getAllSubunits(){

        $subunit =Subunit::query()->available()->orderBy('id','desc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $subunit,
        );
    }

    public function filterSubunits($pagination,$request){

        $subunit =Subunit::query()->available();

        if(count($request->all()) > 0){
            if($request->has('major')){
                $subunit->whereIn('major', $request->input('major'));
            }

        }

        $subunit = $subunit->paginate($pagination);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $subunit,
        );
    }

    public function getPaginatedSubunits($pagination,$request){

        $subunit =Subunit::query()->available();

        if(count($request->all()) > 0){

        }

        $subunit = $subunit->paginate($pagination);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $subunit,
        );
    }
    
    public function get_subunit($request){

        $id = $request["id"];

        $subunit =Subunit::findOrFail($id);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $subunit,
        );
    }

    public function getList(){

        $subunit =Subunit::query()->orderBy('order','asc')->get();

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $subunit,
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
            
            $subunitObject = new Subunit;
            $subunitObject->fill($data);

            $subunitObjectArray = $subunitObject->toArray();

            $subunit = Subunit::create($subunitObjectArray);

            if ($request->hasFile('photo')) {
                if ($subunit->getMedia($this->module_name)->first()) {
                    $subunit->getMedia($this->module_name)->first()->delete();
                }
    
                $media = $subunit->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $subunit->photo = $media->getUrl();

                $subunit->save();
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

        Log::info(label_case($this->module_title.' '.__function__)." | '".$subunit->name.'(ID:'.$subunit->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $subunit,
        );
    }

    public function show($id, $subunitId = null){

        Log::info(label_case($this->module_title.' '.__function__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> Subunit::findOrFail($id),
        );
    }

    public function edit($id){

        $subunit = Subunit::findOrFail($id);

        Log::info(label_case($this->module_title.' '.__function__)." | '".$subunit->name.'(ID:'.$subunit->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $subunit,
        );
    }

    public function update(Request $request,$id){

        $data = $request->all();

        DB::beginTransaction();

        try{

            $subunit = new Subunit;
            $subunit->fill($data);
            
            $updating = Subunit::findOrFail($id)->update($subunit->toArray());

            $updated_subunit = Subunit::findOrFail($id);

            if ($request->hasFile('photo')) {
                if ($updated_subunit->getMedia($this->module_name)->first()) {
                    $updated_subunit->getMedia($this->module_name)->first()->delete();
                }
    
                $media = $updated_subunit->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $updated_subunit->photo = $media->getUrl();

                $updated_subunit->save();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$updated_subunit->name.'(ID:'.$updated_subunit->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $updated_subunit,
        );
    }

    public function destroy($id){

        DB::beginTransaction();

        try{
            $subunits = Subunit::findOrFail($id);
    
            $deleted = $subunits->delete();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$subunits->name.', ID:'.$subunits->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $subunits,
        );
    }

    public function trashed(){

        Log::info(label_case($this->module_title.' View'.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> Subunit::bookingonlyTrashed()->get(),
        );
    }

    public function restore($id){

        DB::beginTransaction();

        try{
            $restoring =  Subunit::bookingwithTrashed()->where('id',$id)->restore();
            $subunits = Subunit::findOrFail($id);
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

        Log::info(label_case(__FUNCTION__)." ".$this->module_title.": ".$subunits->name.", ID:".$subunits->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $subunits,
        );
    }

    public function purge($id){
        DB::beginTransaction();

        try{
            $subunits = Subunit::bookingwithTrashed()->findOrFail($id);
    
            $deleted = $subunits->forceDelete();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$subunits->name.', ID:'.$subunits->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $subunits,
        );
    }

    public function import(Request $request){
        $import = Excel::import(new SubunitsImport($request), $request->file('data_file'));
    
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
        
        $units = Unit::pluck('name','id');
        $options = array(
            'units'         => $units,
        );

        return $options;
    }

}