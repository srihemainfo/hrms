<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnlineCourse extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'online_courses';

    protected $dates = [
        'from_date',
        'to_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'course_name',
        'remark',
        'from_date',
        'to_date',
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

    public function getFromDateAttribute($value)
    {
        return $value;
    }

    public function setFromDateAttribute($value)
    {
        $this->attributes['from_date'] = $value;
    }

    public function getToDateAttribute($value)
    {
        return $value;
    }

    public function setToDateAttribute($value)
    {
        $this->attributes['to_date'] = $value;
    }
}
