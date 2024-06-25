<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EducationalDetail extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'educational_details';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'education_type_id',
        'institute_name',
        'institute_location',
        'medium_id',
        'board_or_university',
        'marks',
        'marks_in_percentage',
        'subject_1',
        'mark_1',
        'subject_2',
        'mark_2',
        'subject_3',
        'mark_3',
        'subject_4',
        'mark_4',
        'subject_5',
        'mark_5',
        'subject_6',
        'mark_6',
        'created_at',
        'updated_at',
        'deleted_at',
        'student_id',
        'month_value',
        'study_mode',
        'qualification',
        'course_duration'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function education_type()
    {
        return $this->belongsTo(EducationType::class, 'education_type_id');
    }
    public function user_name()
    {
        return $this->belongsTo(User::class, 'user_name_id');
    }
    public function medium()
    {
        return $this->belongsTo(MediumofStudied::class, 'medium_id');
    }

}
