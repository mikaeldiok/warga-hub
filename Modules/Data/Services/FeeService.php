<?php

namespace Modules\Data\Services;

use Modules\Data\Entities\Unit;
use Modules\Data\Entities\Fee;

use Exception;
use Carbon\Carbon;
use Auth;

use ConsoleTVs\Charts\Classes\Echarts\Chart;
use App\Charts\FeePerStatus;
use App\Exceptions\GeneralException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


use Modules\Data\Imports\FeesImport;
use Modules\Data\Events\FeeRegistered;

use App\Events\Backend\UserCreated;
use App\Events\Backend\UserUpdated;

use App\Models\User;
use App\Models\Userprofile;

class FeeService{

    public function __construct()
        {        
        $this->module_title = Str::plural(class_basename(Fee::class));
        $this->module_name = Str::lower($this->module_title);
        
        }

    public function list(){

        Log::info(label_case($this->module_title.' '.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        $fee =Fee::join('units', 'units.id', '=', 'fees.unit_id')
                    ->orderBy('units.sequence','asc')
                    ->get(['fees.*']);

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $fee,
        );
    }
    
    public function getAllFees(){

        $fee =Fee::query()->available()->orderBy('id','desc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $fee,
        );
    }

    public function filterFees($pagination,$request){

        $fee =Fee::query()->available();

        if(count($request->all()) > 0){
            if($request->has('major')){
                $fee->whereIn('major', $request->input('major'));
            }

        }

        $fee = $fee->paginate($pagination);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $fee,
        );
    }

    public function getPaginatedFees($pagination,$request){

        $fee =Fee::query()->available();

        if(count($request->all()) > 0){

        }

        $fee = $fee->paginate($pagination);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $fee,
        );
    }
    
    public function get_fee($request){

        $id = $request["id"];

        $fee =Fee::findOrFail($id);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $fee,
        );
    }

    public function getList(){

        $fee =Fee::query()->orderBy('order','asc')->get();

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $fee,
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
            
            $feeObject = new Fee;
            $feeObject->fill($data);

            $feeObjectArray = $feeObject->toArray();

            $fee = Fee::create($feeObjectArray);

            if ($request->hasFile('photo')) {
                if ($fee->getMedia($this->module_name)->first()) {
                    $fee->getMedia($this->module_name)->first()->delete();
                }
    
                $media = $fee->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $fee->photo = $media->getUrl();

                $fee->save();
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

        Log::info(label_case($this->module_title.' '.__function__)." | '".$fee->name.'(ID:'.$fee->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $fee,
        );
    }

    public function show($id, $feeId = null){

        Log::info(label_case($this->module_title.' '.__function__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> Fee::findOrFail($id),
        );
    }

    public function edit($id){

        $fee = Fee::findOrFail($id);

        Log::info(label_case($this->module_title.' '.__function__)." | '".$fee->name.'(ID:'.$fee->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $fee,
        );
    }

    public function update(Request $request,$id){

        $data = $request->all();

        DB::beginTransaction();

        try{

            $fee = new Fee;
            $fee->fill($data);
            
            $updating = Fee::findOrFail($id)->update($fee->toArray());

            $updated_fee = Fee::findOrFail($id);

            if ($request->hasFile('photo')) {
                if ($updated_fee->getMedia($this->module_name)->first()) {
                    $updated_fee->getMedia($this->module_name)->first()->delete();
                }
    
                $media = $updated_fee->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $updated_fee->photo = $media->getUrl();

                $updated_fee->save();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$updated_fee->name.'(ID:'.$updated_fee->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $updated_fee,
        );
    }

    public function destroy($id){

        DB::beginTransaction();

        try{
            $fees = Fee::findOrFail($id);
    
            $deleted = $fees->delete();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$fees->name.', ID:'.$fees->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $fees,
        );
    }

    public function trashed(){

        Log::info(label_case($this->module_title.' View'.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> Fee::bookingonlyTrashed()->get(),
        );
    }

    public function restore($id){

        DB::beginTransaction();

        try{
            $restoring =  Fee::bookingwithTrashed()->where('id',$id)->restore();
            $fees = Fee::findOrFail($id);
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

        Log::info(label_case(__FUNCTION__)." ".$this->module_title.": ".$fees->name.", ID:".$fees->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $fees,
        );
    }

    public function purge($id){
        DB::beginTransaction();

        try{
            $fees = Fee::bookingwithTrashed()->findOrFail($id);
    
            $deleted = $fees->forceDelete();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$fees->name.', ID:'.$fees->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $fees,
        );
    }

    public function import(Request $request){
        $import = Excel::import(new FeesImport($request), $request->file('data_file'));
    
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