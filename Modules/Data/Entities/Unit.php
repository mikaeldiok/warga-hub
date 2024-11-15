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

use Modules\Data\Database\factories\UnitFactory;

class Unit extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "units";
    protected static $logName = 'units';
    protected static $logOnlyDirty = true;
    protected static $logAttributes = ['name', 'id'];

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\UnitFactory::new();
    }

    public function subunits(){
        return $this->hasMany('Modules\Data\Entities\Subunit');
    }

    public function fees(){
        return $this->hasMany('Modules\Data\Entities\Fee');
    }

    public function parameters(){
        return $this->hasMany('Modules\Performance\Entities\Parameter');
    }


    public function getTotalStudentAttribute(){
        return $this->subunits->sum('students_count');
    }

    public function getTotalStaffAttribute(){
        return $this->teacher_count + $this->staff_count;
    }
}

