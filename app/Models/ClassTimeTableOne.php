<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ClassTimeTableOne extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'class_time_table_one';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',

    ];

    protected $fillable = [
        'class_name',
        'day',
        'period',
        'subject',
        'staff',
        'status',
        'rejected_reason',
        'day_order',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function block()
    {
        return $this->belongsTo(CollegeBlock::class, 'block_id');
    }

    public function enroll_master()
    {
        return $this->belongsTo(CourseEnrollMaster::class, 'name');
    }

    public function department()
    {
        return $this->belongsTo(ToolsDepartment::class, 'department_id');
    }

    public function staffs()
    {
        return $this->belongsTo(TeachingStaff::class,'staff', 'user_name_id');
    }

    public function non_tech_staffs()
    {
        return $this->belongsTo(NonTeachingStaff::class,'staff', 'user_name_id');
    }

    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject');
    }

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });
    }
}
