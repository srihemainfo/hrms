<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndustrialExperience extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'industrial_experiences';

    protected $dates = [
        'from',
        'to',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'work_experience',
        'designation',
        'from',
        'to',
        'work_type',
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

    public function getFromAttribute($value)
    {
        return $value;
    }

    public function setFromAttribute($value)
    {
        $this->attributes['from'] = $value;
    }

    public function getToAttribute($value)
    {
        return $value;
    }

    public function setToAttribute($value)
    {
        $this->attributes['to'] = $value;
    }
}
