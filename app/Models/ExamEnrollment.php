<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamEnrollment extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'exam_enrollment';

    protected $fillable = [
        'academic_year',
        'batch',
        'course',
        'semester',
        'regulation',
        'user_name_id',
        'subject_name',
        'subject_type',
        'register_no',
        'subject_code',
        'subject_id',
        'subject_sem',
        'enrolled_date',
        'credits',
        'exam_type',
        'exam_fee',
        'exam_month',
        'exam_year',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

}
