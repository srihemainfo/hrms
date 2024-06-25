<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leave extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'leaves';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'role_id',
        'casual_leave',
        'on_duty',
        'admin_od',
        'exam_od',
        'training_od',
        'compensation_leave',
        'maternity_leave',
        'medical_leave',
        'marriage_leave',
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
        return $this->belongsTo(User::class, 'user_id');
    }

}
