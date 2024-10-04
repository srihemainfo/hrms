<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentPeriodAllocate extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'students_period_allocation';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'class',
        'batch',
        'student',
        'created_at',
        'updated_at',
        'deleted_at',
        'updated_by',
    ];

    public function user_name()
    {
        return $this->belongsTo(User::class, 'student');
    }

    public function enroll_master()
    {
        return $this->belongsTo(CourseEnrollMaster::class, 'class');
    }

}
