<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffOldCurrentStatus extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'staff_old_current_statuses';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $day = [
        'start_time',
        'end_time',
    ];


    protected $fillable = [
        'staff_name',
        'user_name_id',
        'status',
        'current_status',
        'teach_or_nonteach',
        'Dept',
        'Designation',
        'start_time',
        'end_time',
        'total_days',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    protected function serializeDate2(DateTimeInterface $day)
    {
        return $day->format('Y-m-d');
    }

    public static function last()
    {
        return self::oldest()->first();
    }

    public function PersonalDetail()
    {
        return $this->belongsTo(PersonalDetail::class);
    }
}

