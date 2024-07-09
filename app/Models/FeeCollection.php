<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeCollection extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'fee_collection';

    protected $fillable = [
        'register_no',
        'student_name',
        'student_id',
        'receipt_no',
        'semester',
        'total_amount',
        'paid_amount',
        'paid_date',
        'settlement_date',
        'fees_id',
        'transaction_id',
        'payment_type',
        'status',
        'remarks',
        'cron',
        'created_at',
        'updated_at',
        'deleted_at',

    ];

    public function EnrollMaster()
    {
        return $this->belongsTo(CourseEnrollMaster::class, 'enroll_master');
    }
    public function Student()
    {
        return $this->belongsTo(Student::class, 'user_name_id', 'user_name_id');
    }
    public function Fee()
    {
        return $this->belongsTo(FeeStructure::class, 'fee_id');
    }
    public function AY()
    {
        return $this->belongsTo(AcademicDetail::class, 'user_name_id', 'user_name_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

}
