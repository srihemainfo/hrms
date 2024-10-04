<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectAllotment extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'subject_allotment';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'regulation',
        'department',
        'course',
        'academic_year',
        'semester_type',
        'subject_id',
        'semester',
        'category',
        'subject_code',
        'subject_name',
        'subject_type',
        'credits',
        'option_limits',
        'created_at',
        'updated_at',
        'deleted_at',
        'subject_id'
    ];

    public function regulations()
    {
        return $this->belongsTo(ToolssyllabusYear::class, 'regulation');
    }

    public function courses()
    {
        return $this->belongsTo(ToolsCourse::class, 'course');
    }

    public function departments()
    {
        return $this->belongsTo(ToolsDepartment::class, 'department');
    }

    public function semesters()
    {
        return $this->belongsTo(Semester::class, 'semester');
    }

    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function academic_years()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year');
    }

}
