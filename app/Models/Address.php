<?php

namespace App\Models;


use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'addresses';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const ADDRESS_TYPE_SELECT = [
        'temparory' => 'temparory',
        'permanent' => 'permanent',
    ];

    protected $fillable = [
        'address_type',
        'name_id',
        'room_no_and_street',
        'area_name',
        'district',
        'pincode',
        'state',
        'country',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
        'student_id'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function name()
    {
        return $this->belongsTo(User::class, 'name_id');
    }
}
