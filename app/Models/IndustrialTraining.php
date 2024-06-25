<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndustrialTraining extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'industrial_trainings';

    protected $dates = [
        'from_date',
        'to_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name_id',
        'topic',
        'location',
        'remarks',
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

    public function name()
    {
        return $this->belongsTo(User::class, 'name_id');
    }

    public function getFromDateAttribute($value)
    {
        // return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
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
        // $this->attributes['to_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
        $this->attributes['to_date'] = $value;
    }
}
