<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignmentModel extends Model
{
    use SoftDeletes, Auditable, HasFactory;
    public $table = 'assignment_table';
    protected $dates = [

        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'academic_year',
        'semester_type',
        'course_id',
        'year',
        'semester',
        'due_date',
        'subject',
        'section',
        'exam_name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
