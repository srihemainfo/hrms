<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceRecord extends Model
{
    use SoftDeletes,Auditable, HasFactory;

    public $table = 'attendance_record';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'student',
        'subject',
        'date',
        'day',
        'period',
        'attendance',
        'staff',
        'status',
        'enroll_master',
        'topic',
        'unit',
        'actual_date',
        'reason',
        'lab_batch',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject');
    }

    public function staffs()
    {
        return $this->belongsTo(User::class, 'staff');
    }

}
