<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffTransferInfo extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'staff_transfer_infos';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'enroll_master_id',
        'period',
        'from_user',
        'to_user',
        'transfer_date',
        'approved_by_user',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function enroll_master()
    {
        return $this->belongsTo(CourseEnrollMaster::class, 'enroll_master_id');
    }
}
