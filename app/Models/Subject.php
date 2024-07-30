<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'subjects';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'regulation_id',
        'semester_id',
        'course_id',
        'department_id',
        'subject_code',
        'status',
        'subject_type_id',
        'subject_cat_id',
        'lecture',
        'tutorial',
        'practical',
        'credits',
        'contact_periods',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function regulation()
    {
        return $this->belongsTo(ToolssyllabusYear::class, 'regulation_id');
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

    public function subject_type()
    {
        return $this->belongsTo(SubjectType::class, 'subject_type_id');
    }

    public function subject_category()
    {
        return $this->belongsTo(SubjectCategory::class, 'subject_cat_id');
    }
    public function subject_register()
    {
        return $this->hasMany(SubjectRegistration::class, 'id', 'subject');
    }
}
