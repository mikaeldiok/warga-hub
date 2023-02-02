<?php

namespace Modules\School\Services;

use Modules\School\Entities\Core;

use Exception;
use Carbon\Carbon;
use Auth;

use App\Exceptions\GeneralException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\User;
use App\Models\Userprofile;

class CoreService{

    public function __construct()
        {        
        $this->module_title = Str::plural(class_basename(Core::class));
        $this->module_name = Str::lower($this->module_title);
        
        }

    public function list(){

        Log::info(label_case($this->module_title.' '.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        $core =Core::query()->orderBy('id','desc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $core,
        );
    }

    public function getAllCores(){

        $core =Core::query()->orderBy('id','desc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $core,
        );
    }

    public function getPaginatedCores($pagination,$parameters = null){

        $core =Core::query();

        if($parameters){
            foreach($parameters as $parameter_key => $parameter_value)
            {
                $core->where($parameter_key,$parameter_value);
            }
        }

        $core = $core->paginate($pagination);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $core,
        );
    }
    
    public function get_core($request){

        $id = $request["id"];

        $core =Core::findOrFail($id);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $core,
        );
    }

    public function getList(){

        $core =Core::query()->orderBy('order','asc')->get();

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $core,
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
            
            $coreObject = new Core;
            $coreObject->fill($data);

            $coreObjectArray = $coreObject->toArray();

            $core = Core::create($coreObjectArray);

            if ($request->hasFile('photo')) {
                if ($core->getMedia($this->module_name)->first()) {
                    $core->getMedia($this->module_name)->first()->delete();
                }
    
                $media = $core->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $core->photo = $media->getUrl();

                $core->save();
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

        Log::info(label_case($this->module_title.' '.__function__)." | '".$core->name.'(ID:'.$core->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $core,
        );
    }

    public function show($id, $coreId = null){

        \Log::debug("id ser". $id);
        \Log::debug("coreId ser". $coreId);

        Log::info(label_case($this->module_title.' '.__function__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> Core::findOrFail($id),
        );
    }

    public function edit($id){

        $core = Core::findOrFail($id);

        Log::info(label_case($this->module_title.' '.__function__)." | '".$core->name.'(ID:'.$core->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $core,
        );
    }

    public function update(Request $request,$id){

        $data = $request->all();

        DB::beginTransaction();

        try{

            $core = new Core;
            $core->fill($data);
            
            if($core->birth_date){
                $core->birth_date = Carbon::createFromFormat('d/m/Y', $core->birth_date)->format('Y-m-d'); 
            }

            $updating = Core::findOrFail($id)->update($core->toArray());

            $updated_core = Core::findOrFail($id);

            if ($request->hasFile('photo')) {
                if ($updated_core->getMedia($this->module_name)->first()) {
                    $updated_core->getMedia($this->module_name)->first()->delete();
                }
    
                $media = $updated_core->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $updated_core->photo = $media->getUrl();

                $updated_core->save();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$updated_core->name.'(ID:'.$updated_core->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $updated_core,
        );
    }

    public function destroy($id){

        DB::beginTransaction();

        try{
            $cores = Core::findOrFail($id);
    
            $deleted = $cores->delete();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$cores->name.', ID:'.$cores->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $cores,
        );
    }

    public function trashed(){

        Log::info(label_case($this->module_title.' View'.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> Core::onlyTrashed()->get(),
        );
    }

    public function restore($id){

        DB::beginTransaction();

        try{
            $restoring =  Core::withTrashed()->where('id',$id)->restore();
            $cores = Core::findOrFail($id);
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

        Log::info(label_case(__FUNCTION__)." ".$this->module_title.": ".$cores->name.", ID:".$cores->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $cores,
        );
    }

    public function purge($id){
        DB::beginTransaction();

        try{
            $cores = Core::withTrashed()->findOrFail($id);
    
            $deleted = $cores->forceDelete();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$cores->name.', ID:'.$cores->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $cores,
        );
    }

    public function import(Request $request){
        $import = Excel::import(new CoresImport($request), $request->file('data_file'));
    
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $import,
        );
    }

    public function prepareOptions(){
        
        $banks= [];
        $bank_names= [];

        $raw_banks = config('banks');

        foreach($raw_banks as $raw_bank){
            $banks = Arr::add($banks, $raw_bank['code'].'-'.$raw_bank['name'], $raw_bank['code'].' - '.$raw_bank['name'] );
            $bank_names = Arr::add($bank_names, $raw_bank['name'], $raw_bank['name'] );
        }

        $core_types = [
            'institusi'     => 'Institusi',
            'perorangan'     => 'Perorangan',
        ];

        $options = array(
            'banks'         => $banks,
            'bank_names'    => $bank_names,
            'core_types' => $core_types,
        );

        return $options;
    }

}