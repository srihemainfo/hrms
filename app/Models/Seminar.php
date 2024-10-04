<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seminar extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'seminars';

    protected $dates = [
        'seminar_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'topic',
        'remark',
        'seminar_date',
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

    public function getSeminarDateAttribute($value)
    {
        return $value ;
        // ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setSeminarDateAttribute($value)
    {
        $this->attributes['seminar_date'] = $value ;
        // ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
}
