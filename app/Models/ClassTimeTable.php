<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;


class ClassTimeTable extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'class_time_tables';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
     
    ];

    protected $fillable = [
        'class_name',
        'day',
        'p_one_subject',
        'p_one_staff',
        'p_two_subject',
        'p_two_staff',
        'p_three_subject',
        'p_three_staff',
        'p_four_subject',
        'p_four_staff',
        'p_five_subject',
        'p_five_staff',
        'p_six_subject',
        'p_six_staff',
        'p_seven_subject',
        'p_seven_staff',
        'day_order',
        'status',
        'created_by',
        'rejected_reason',
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

    public function teaching_staff()
    {
        return $this->belongsTo(TeachingStaff::class, 'class_incharge');
    }

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });
    }
}
