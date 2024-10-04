<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignmentData extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'assignment_data';
    protected $dates = [

        'created_at',
        'updated_at',];

        protected $fillable = [
            'date',
            'enter_by',
            'class_id',
            'subject',
            'semester',
            'mark_entry',
            'edited_by',
            'student_id',
           'assignment_name_id',
            'assignment_mark_1',
            'assignment_mark_2',
            'assignment_mark_3',
            'assignment_mark_4',
            'assignment_mark_5',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
     
}
