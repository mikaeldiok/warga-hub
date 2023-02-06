<?php

namespace Modules\System\Entities;

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

use Modules\System\Database\factories\GroupFactory;

class Group extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "groups";

    protected static $logName = 'groups';
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

    public function appsites(){
        return $this->hasMany('Modules\System\Entities\Appsite');
    }
}

