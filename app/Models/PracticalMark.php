<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PracticalMark extends Model
{
    use SoftDeletes,Auditable, HasFactory;

    public $table = 'practical_marks';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'batch',
        'ay',
        'course',
        'semester',
        'exam_type',
        'exam_month',
        'exam_year',
        'subject',
        'subject_sem',
        'mark',
        'mark_in_word',
        'action',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_name_id', 'user_name_id');
    }

    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject');
    }

    public function courses()
    {
        return $this->belongsTo(ToolsCourse::class, 'course');
    }
    public function batches()
    {
        return $this->belongsTo(Batch::class, 'batch');
    }
    public function ays()
    {
        return $this->belongsTo(AcademicYear::class, 'ay');
    }

}
