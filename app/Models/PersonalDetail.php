<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonalDetail extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'personal_details';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'age',
        'dob',
        'name',
        'email',
        'gender',
        'gender',
        'student_id',
        'staff_id',
        'mobile_number',
        'aadhar_number',
        'blood_group_id',
        'mother_tongue_id',
        'religion_id',
        'community_id',
        'state',
        'country',
        'created_at',
        'updated_at',
        'deleted_at',
        'last_name',
        'father_name',
        'spouse_name',
        'StaffCode',
        'BiometricID',
        'AICTE',
        'PanNo',
        'DOJ',
        'DOR',
        'au_card_no',
        'COECode',
        'PassportNo',
        'employment_type',
        'employment_status',
        'rit_club_incharge',
        'future_tech_membership',
        'future_tech_membership_type',
        'known_languages',
        'emergency_contact_no',
        'caste',
        'different_abled_person',
        'whatsapp_no',
        'annual_income',
        'first_graduate'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user_name()
    {
        return $this->belongsTo(User::class, 'user_name_id');
    }

    public function getDobAttribute($value)
    {
        // return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
        return $value;
    }

    public function setDobAttribute($value)
    {
        // $this->attributes['dob'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
        $this->attributes['dob'] = $value;
    }

    public function blood_group()
    {
        return $this->belongsTo(BloodGroup::class, 'blood_group_id');
    }

    public function mother_tongue()
    {
        return $this->belongsTo(MotherTongue::class, 'mother_tongue_id');
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class, 'religion_id');
    }

    public function community()
    {
        return $this->belongsTo(Community::class, 'community_id');
    }
    public function department()
    {
        return $this->belongsTo(ToolsDepartment::class, 'department_id');
    }

}
