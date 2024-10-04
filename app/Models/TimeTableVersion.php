<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeTableVersion extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'timetable_versions';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',

    ];

    protected $fillable = [
        'data',
        'version',
        'class_id',
        'approved_by',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function enroll_master()
    {
        return $this->belongsTo(CourseEnrollMaster::class, 'class_id');
    }

    public function staffs()
    {
        return $this->belongsTo(TeachingStaff::class, 'staff', 'user_name_id');
    }

    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject');
    }

}
