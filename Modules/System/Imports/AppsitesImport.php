<?php

namespace Modules\System\Imports;

use App\Overrides\Zip;
use ZipArchive;
use Carbon\Carbon;
use Modules\System\Entities\Appsite;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AppsiteImport implements ToCollection, WithHeadingRow
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->module_title = Str::plural(class_basename(Appsite::class));
        $this->module_name = Str::lower($this->module_title);
        $this->request = $request;
    }

    public function collection(Collection $rows)
    {
        $zip = null;
        
        if ($this->request->hasFile('photo_file')){
            $storezippath = \Storage::putFile('zipfile', $this->request->file('photo_file'));
            $zip = Zip::open(storage_path("app/".$storezippath));
        }

        foreach ($rows as $row) {
            
            if($row['birth_date']){
                $birth_date = Carbon::createFromFormat('d/m/Y', $row['birth_date'])->format('Y-m-d'); 
            }

            $appsite = Appsite::create([
                            'name'                      => $row['name'],
                            'appsite_id'                => $row['appsite_id'],
                            'gender'                    => $row['gender'],
                            'birth_place'               => $row['birth_place'],
                            'birth_date'                => $birth_date,
                            'year_class'                => $row['year_class'],
                            'major'                     => $row['major'],
                            'height'                    => $row['height'],
                            'weight'                    => $row['weight'],
                            'religion'                  => $row['religion'],
                            'photo'                     => "",
                        ]);

            if($zip){
                $photoExist = $zip->has($row['appsite_id'].".jpg", ZipArchive::FL_NOCASE|ZipArchive::FL_NODIR);
                
                // \Log::debug($row['appsite_id']."jpeg");
                // \Log::debug($zip->has($row['appsite_id'].".jpeg", ZipArchive::FL_NOCASE|ZipArchive::FL_NODIR));
                // \Log::debug($zip->has($row['appsite_id'].".jpg", ZipArchive::FL_NOCASE|ZipArchive::FL_NODIR));
                // \Log::debug($zip->has($row['appsite_id'], ZipArchive::FL_NOCASE|ZipArchive::FL_NODIR));
                
                if($photoExist){
                    $isExtracted = $zip->extract(storage_path('app/ziptmp'), $row['appsite_id'].".jpg");

                    if($isExtracted){
                        $photo =  \Storage::get('ziptmp/'.$row['appsite_id'].".jpg");
                        if ($appsite->getMedia($this->module_name)->first()) {
                            $appsite->getMedia($this->module_name)->first()->delete();
                        }
            
                        $media = $appsite->addMediaFromDisk('ziptmp/'.$row['appsite_id'].".jpg",'local')->toMediaCollection($this->module_name);
        
                        $appsite->photo = $media->getUrl();
        
                        $appsite->save();
                    }

                }

            }

        }
    }
}