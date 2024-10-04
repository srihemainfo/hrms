<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionRequest extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'permissionrequests';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'from_time',
        'to_time',
        'date',
        'reason',
        'approved_by',
        'status',
        'rejected_reason',
        'user_name_id',
        'name',
        'staff_code',
        'biometric_id',
        'dept',
        'Permission',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    public function user_name()
    {
        return $this->belongsTo(User::class, 'user_name_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_name_id');
    }

}
