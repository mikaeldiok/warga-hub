<?php

namespace Modules\Core\Services;

use Modules\Core\Repositories\UnitRepository;

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


use Modules\Core\Imports\UnitsImport;

class UnitService{

    protected $unitRepository;

    public function __construct(
        UnitRepository $unitRepository
        )
        {        
        $this->unitRepository = $unitRepository;

        $this->module_title = Str::plural(class_basename($this->unitRepository->model()));

    }

    public function list(){

        Log::info(label_case($this->module_title.' '.__FUNCTION__).' | User:'.Auth::user()->name.'(ID:'.Auth::user()->id.')');

        $unit =$this->unitRepository->query()->orderBy('order','asc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $unit,
        );
    }

    public function getList(){

        $unit =$this->unitRepository->query()->orderBy('order','asc')->get();

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $unit,
        );
    }


    public function create(){

        Log::info(label_case($this->module_title.' '.__function__).' | User:'.Auth::user()->name.'(ID:'.Auth::user()->id.')');

        $createOptions = [];

        return $createOptions;
    }

    public function store(Request $request){

        $data = $request->all();

        DB::beginTransaction();

        try {
            $unitObject = $this->unitRepository->make($data);
            $unit = $this->unitRepository->create($unitObject->toArray());

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

        Log::info(label_case($this->module_title.' '.__function__)." | '".$unit->name.'(ID:'.$unit->id.") ' by User:".Auth::user()->name.'(ID:'.Auth::user()->id.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $unit,
        );
    }

    public function show($id){

        Log::info(label_case($this->module_title.' '.__function__).' | User:'.Auth::user()->name.'(ID:'.Auth::user()->id.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $this->unitRepository->findOrFail($id),
        );
    }

    public function edit($id){

        $unit = $this->unitRepository->findOrFail($id);

        $unit->installment_ids = json_decode($unit->installment_ids, true);

        Log::info(label_case($this->module_title.' '.__function__)." | '".$unit->name.'(ID:'.$unit->id.") ' by User:".Auth::user()->name.'(ID:'.Auth::user()->id.')');

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
            $unit = $this->unitRepository->make($data);

            $updated = $this->unitRepository->update($unit->toArray(),$id);

            $updated_unit = $this->unitRepository->findOrFail($id);
            
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$updated_unit->name.'(ID:'.$updated_unit->id.") ' by User:".Auth::user()->name.'(ID:'.Auth::user()->id.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $updated_unit,
        );
    }

    public function destroy($id){

        DB::beginTransaction();

        try{
            $units = $this->unitRepository->findOrFail($id);
    
            $deleted = $this->unitRepository->delete($id);
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$units->name.', ID:'.$units->id." ' by User:".Auth::user()->name.'(ID:'.Auth::user()->id.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $units,
        );
    }

    public function trashed(){

        Log::info(label_case($this->module_title.' View'.__FUNCTION__).' | User:'.Auth::user()->name.'(ID:'.Auth::user()->id.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $this->unitRepository->trashed(),
        );
    }

    public function restore($id){

        DB::beginTransaction();

        try{
            $units= $this->unitRepository->restore($id);
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

        Log::info(label_case(__FUNCTION__)." ".$this->module_title.": ".$units->name.", ID:".$units->id." ' by User:".Auth::user()->name.'(ID:'.Auth::user()->id.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $units,
        );
    }

    public function purge($id){
        DB::beginTransaction();

        try{
            $units = $this->unitRepository->findTrash($id);
    
            $deleted = $this->unitRepository->purge($id);
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$units->name.', ID:'.$units->id." ' by User:".Auth::user()->name.'(ID:'.Auth::user()->id.')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $units,
        );
    }

    public function import(Request $request){
                 
        $import = Excel::import(new UnitsImport, $request->file('data_file'));

        
    
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $import,
        );
    }
}