<?php

namespace Modules\Performance\Entities;

use Auth;
use DB;
use Carbon\Carbon;
use App\Models\BaseModel;
use App\Models\Traits\HasHashedMediaTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

use Modules\Performance\Database\factories\ParameterFactory;

class Parameter extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "parameters";

    protected static $logName = 'parameters';
    protected static $logOnlyDirty = true;
    protected static $logAttributes = ['name', 'id'];

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\UnitFactory::new();
    }

    public function scopeAvailable($query){
        return $query->where('available','1');
    }

    public function isAvailable(){
        return $this->available;
    }

    public function unit(){
        return $this->belongsTo('Modules\Data\Entities\Unit');
    }
}

