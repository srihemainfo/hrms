<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignmentAttendances extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'assignment_attendances';
    protected $dates = [

        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'assignment_id',
        'course',
        'subject',
        'academic_year',
        'exam_name',
        'year',
        'semester',
        'section',
        'assignment_mark',
        'mark_enter_by',
        'mark_date',
        'mark_entry',
        'status',
        'update_by',
        'publish_by',
        'final_submit_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
