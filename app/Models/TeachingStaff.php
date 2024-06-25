<?php

namespace App\Models;

use App\Models\ClassTimeTableTwo;
use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeachingStaff extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'teaching_staffs';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'last_name',
        'subject_id',
        'enroll_master_id',
        'working_as_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'StaffCode',
        'BiometricID',
        'Gender',
        'Designation',
        'Dept',
        'Qualification',
        'DOJ',
        'DOR',
        'OtherEnggCollegeExperience',
        'TotalExperience',
        'ContactNo',
        'EmailIDOffical',
        'Religion',
        'Community',
        'PanNo',
        'PassportNo',
        'AadharNo',
        'COECode',
        'AICTE',
        'DOB',
        'HighestDegree',
        'user_name_id',
        'TotalSalary',
        'basicPay',
        'agp',
        'specialFee',
        'phdAllowance',
        'da',
        'hra',
        'au_card_no',
        'casual_leave',
        'casual_leave_taken',
        'subtracted_cl',
        'compensation_leave',
        'personal_permission',
        'past_personal_permission',
        'personal_permission_taken',
        'admin_od',
        'exam_od',
        'training_od',
        'rd_staff',
        'role_type'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function user_name()
    {
        return $this->belongsTo(User::class, 'user_name_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function enroll_master()
    {
        return $this->belongsTo(CourseEnrollMaster::class, 'enroll_master_id');
    }

    public function working_as()
    {
        return $this->belongsTo(Role::class, 'working_as_id');
    }

    public function timeTable(){
        return $this->hasMany(ClassTimeTableTwo::class, 'staff','user_name_id');

    }

    public function personal_details()
{
    return $this->belongsTo(PersonalDetail::class, 'user_name_id', 'user_name_id');
}

}
