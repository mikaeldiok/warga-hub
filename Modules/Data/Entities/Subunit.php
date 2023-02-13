<?php

namespace Modules\Data\Entities;

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

use Modules\Data\Database\factories\SubunitFactory;

class Subunit extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "subunits";

    protected static $logName = 'subunits';
    protected static $logOnlyDirty = true;
    protected static $logAttributes = ['name', 'id'];

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\SubunitFactory::new();
    }

    public function unit(){
        return $this->belongsTo('Modules\Data\Entities\Unit');
    }
}

