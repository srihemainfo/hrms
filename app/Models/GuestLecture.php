<?php

namespace App\Models;


use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuestLecture extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'guest_lectures';

    protected $dates = [
        'from_date_and_time',
        'to_date_and_time',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'topic',
        'remarks',
        'location',
        'from_date_and_time',
        'to_date_and_time',
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

    public function getFromDateAndTimeAttribute($value)
    {
        // return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
        return $value;
    }

    public function setFromDateAndTimeAttribute($value)
    {
        // $this->attributes['from_date_and_time'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
        $this->attributes['from_date_and_time'] = $value;
    }

    public function getToDateAndTimeAttribute($value)
    {
        return $value;
    }

    public function setToDateAndTimeAttribute($value)
    {
        $this->attributes['to_date_and_time'] = $value;
    }
}
