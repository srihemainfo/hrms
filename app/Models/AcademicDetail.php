<?php

namespace App\Models;


use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicDetail extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'academic_details';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'enroll_master_number_id',
        'register_number',
        'emis_number',
        'roll_no',
        'admitted_mode',
        'admitted_course',
        'scholarship',
        'first_graduate',
        'gqg',
        'batch',
        'user_name_id',
        'late_entry',
        'hosteler',
        'scholarship_name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function user_name()
    {
        return $this->belongsTo(User::class, 'user_name_id');
    }

    public function enroll_master_number()
    {
        return $this->belongsTo(CourseEnrollMaster::class, 'enroll_master_number_id');
    }

    public function course()
    {
        return $this->belongsTo(ToolsCourse::class, 'admitted_course');
    }

    public function scholarDetail()
    {
        return $this->belongsTo(Scholarship::class, 'scholarship_name');
    }
}
