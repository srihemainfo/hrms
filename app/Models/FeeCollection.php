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
        'user_name_id',
        'enroll_master',
        'year',
        'fee_id',
        'date',
        'payment_mode',
        'total_fee',
        'total_paid',
        'last_paid',
        'total_paying',
        'total_balance',
        'tuition_last_paid',
        'tuition_paid',
        'tuition_paying',
        'tuition_balance',
        'hostel_last_paid',
        'hostel_paid',
        'hostel_paying',
        'hostel_balance',
        'other_last_paid',
        'other_paid',
        'other_paying',
        'other_balance',
        'sponser_amt',
        'fg_deduction',
        'hosteler',
        'status',
        'created_by',
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
        return $this->belongsTo(Student::class,'user_name_id','user_name_id');
    }
    public function Fee(){
        return $this->belongsTo(FeeStructure::class, 'fee_id');
    }
    public function AY(){
        return $this->belongsTo(AcademicDetail::class,'user_name_id','user_name_id');
    }
}
