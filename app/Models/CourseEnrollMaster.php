<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseEnrollMaster extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'course_enroll_masters';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'enroll_master_number',
        'degreetype_id',
        'batch_id',
        'academic_id',
        'course_id',
        'department_id',
        'semester_id',
        'section_id',
        'deletes',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function degreetype()
    {
        return $this->belongsTo(ToolsDegreeType::class, 'degreetype_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function academic()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_id');
    }

    public function course()
    {
        return $this->belongsTo(ToolsCourse::class, 'course_id');
    }

    public function department()
    {
        return $this->belongsTo(ToolsDepartment::class, 'department_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
