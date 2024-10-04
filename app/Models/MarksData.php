<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarksData extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'marks_data';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'batch',
        'academic_year',
        'course',
        'semester',
        'enroll_master',
        'subject_id',
        'subject_type',
        'co_1',
        'co_2',
        'co_3',
        'co_4',
        'co_5',
        'tco_1',
        'tco_2',
        'tco_3',
        'tco_4',
        'tco_5',
        'tcy_1',
        'tcy_2',
        'cy_1',
        'cy_2',
        'tp_1',
        'p_1',
        'tp_2',
        'p_2',
        'tp_3',
        'p_3',
        'tmod_1',
        'mod_1',
        'as',
        'tas',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getStudent()
    {
        return $this->belongsTo(Student::class, 'user_name_id', 'user_name_id');
    }

    public function getCourse()
    {
        return $this->belongsTo(ToolsCourse::class, 'course');
    }

    public function getAy()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year');
    }

    public function getBatch()
    {
        return $this->belongsTo(Batch::class, 'batch');
    }
}
