<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentMarks extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'student_marks';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'class',
        'subject',
        'exam',
        'marks',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at',

    ];
}
