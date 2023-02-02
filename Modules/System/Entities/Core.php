<?php

namespace Modules\Mkstarter\Entities;

use Auth;
use DB;
use Carbon\Carbon;
use App\Models\BaseModel;
use App\Models\Traits\HasHashedMediaTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Foundation\Auth\User as UserModel;
use Spatie\Permission\Traits\HasRoles;

use Modules\Recruiter\Entities\Booking;

class Core extends UserModel implements HasMedia
{
    use HasHashedMediaTrait;
    use HasRoles;

    use HasFactory;
    use SoftDeletes;

    protected $table = "mkstarter_cores";

    protected static $logName = 'mkstarter_cores';
    protected static $logOnlyDirty = true;
    protected static $logAttributes = ['name', 'id'];
    
    protected $guarded = [
        'id',
        'updated_at',
        '_token',
        '_method',
    ];
    
    protected static function boot()
    {
        parent::boot();

        // create a event to happen on creating
        static::creating(function ($table) {
            $table->created_by = Auth::id();
            $table->created_at = Carbon::now();
        });

        // create a event to happen on updating
        static::updating(function ($table) {
            $table->updated_by = Auth::id();
        });

        // create a event to happen on saving
        static::saving(function ($table) {
            $table->updated_by = Auth::id();
        });

        // create a event to happen on deleting
        static::deleting(function ($table) {
            $table->deleted_by = Auth::id();
            $table->save();
        });
    }


    /**
     * Get the list of all the Columns of the table.
     *
     * @return array Column names array
     */
    public function getTableColumns()
    {
        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
        
        switch($driver){
            case 'mysql':
                    $table_info_columns = DB::select(DB::raw('SHOW COLUMNS FROM '.$this->getTable()));
                break;
            case 'pgsql':       
                    $table_info_columns = DB::select(DB::raw(
                        "SELECT data_type as Type, column_name as Field
                            FROM information_schema.columns
                        Where table_schema = 'public'    
                        AND table_name   = '".$this->getTable()."'"
                    ));
                break;
        }   

        return $table_info_columns;
    }

    public static function getRawData(String $core_code){
        $core_data = self::where('mkstarter_core_code', $core_code)->first();
        $raw_data= explode(",",$core_data->mkstarter_core_value);

        return $raw_data;
    }
}

