<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IssueCertificate extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'issue_certificate';

    protected $fillable = [
        'user_name_id',
        'enroll_master',
        'date',
        'purpose',
        'certificate',
        'status',
        'approved_date',
        'purpose_type',
        'hostelcheck',
        'message',
        'action_reason',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_name_id','user_name_id');
    }

    public function class()
    {
        return $this->belongsTo(CourseEnrollMaster::class, 'enroll_master');
    }
}
