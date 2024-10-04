<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NonTeachingStaff extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'non_teaching_staffs';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'working_as_id',
        'last_name',
        'StaffCode',
        'Dept',
        'Designation',
        'BiometricID',
        'phone',
        'email',
        'user_name_id',
        'past_personal_permission',
        'created_at',
        'updated_at',
        'deleted_at',
        'role_type'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function working_as()
    {
        return $this->belongsTo(Role::class, 'working_as_id');
    }
}
