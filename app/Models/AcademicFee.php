<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicFee extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'academic_fee';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'Register_No', // For Import
        'Name', // For Import
        'Academic_Year', // For Import
        'Scholarship', // For Import
        'GQG', // For Import
        'FG', // For Import
        'Paid_Amount', // For Import
        'paid_date', // For Import
        'user_name_id',
        'enroll_master_id',
        'batch',
        'ay',
        'course',
        'fee_data',
        'scholarship_amt',
        'gqg_amt',
        'fg_amt',
        'paid_amt',
        'tuition_fee',
        'hostel_fee',
        'other_fee',
        'fine',
        'status'
    ];

    public function user_name()
    {
        return $this->belongsTo(User::class, 'user_name_id');
    }

    public function enroll_master_number()
    {
        return $this->belongsTo(CourseEnrollMaster::class, 'enroll_master_number_id');
    }

    public function getAy()
    {
        return $this->belongsTo(AcademicYear::class, 'ay');
    }

    public function academicDetail()
    {
        return $this->belongsTo(AcademicDetail::class, 'user_name_id', 'user_name_id');
    }
}
