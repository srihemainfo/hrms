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
        'role_id',
        'designation_id',
        'name',
        'dob',
        'age',
        'email',
        'phone_number',
        'emergency_contact_no',
        'father_name',
        'spouse_name',
        'gender',
        'blood_group_id',
        'religion_id',
        'aadhar_number',
        'community_id',
        'marial_status',
        'pan_number',
        'total_experience',
        'nationality',
        'state',
        'mother_tongue_id',
        'biometric_id',
        'date_of_joining',
        'date_of_relieving',
        'employee_status',
        'created_at',
        'updated_at',
        'deleted_at'
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
