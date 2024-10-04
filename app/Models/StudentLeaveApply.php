<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentLeaveApply extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'student_leave_apply';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'from_date',
        'to_date',
        'reason',
        'subject',
        'status',
        'leave_type',
        'approved_by',
        'certificate_path',
        'level',
        'status',
        'rejected_reason',
        'enrollno',
        'classincharge',
        'dept',
        'created_at',
        'updated_at',
        'deleted_at',

    ];

    public function user_name()
    {
        return $this->belongsTo(User::class, 'user_name_id');
    }

}
