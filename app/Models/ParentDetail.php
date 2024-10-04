<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParentDetail extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'parent_details';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'father_name',
        'father_mobile_no',
        'father_email',
        'fathers_occupation',
        'father_off_address',
        'mother_name',
        'mother_mobile_no',
        'mother_email',
        'mothers_occupation',
        'mother_off_address',
        'guardian_name',
        'guardian_mobile_no',
        'guardian_email',
        'gaurdian_occupation',
        'guardian_off_address',
        'created_at',
        'updated_at',
        'deleted_at',
        'student_id',
        'user_name_id'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function user_name()
    {
        return $this->belongsTo(User::class, 'user_name_id');
    }
}
