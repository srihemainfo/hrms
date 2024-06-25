<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffAlteration extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'staff_alteration_request';


    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'from_date',
        'to_date',
        'date',
        'from_id',
        'to_id',
        'approval',
        'read',
        'status',
        'period',
        'day',
        'classname',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
