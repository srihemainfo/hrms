<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrmRequestLeaf extends Model
{
    use SoftDeletes,Auditable, HasFactory;

    public $table = 'hrm_request_leaves';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'from_date',
        'to_date',
        'off_date',
        'alter_date',
        'half_day_leave',
        'noon',
        'subject',
        'status',
        'leave_type',
        'balance_cl',
        'approved_by',
        'assigning_staff',
        'total_days_nxt_mn',
        'total_days',
        'level',
        'certificate',
        'rejected_reason',
        'clarification_reason',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
