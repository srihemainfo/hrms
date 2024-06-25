<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sabotical extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'saboticals';

    protected $dates = [
        'from',
        'to',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name_id',
        'topic',
        // 'eligiblity_approve',
        'from',
        'to',
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

    public function getFromAttribute($value)
    {
        return $value;
        //  ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setFromAttribute($value)
    {
        $this->attributes['from'] = $value ;
        // ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getToAttribute($value)
    {
        return $value ;
        // ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setToAttribute($value)
    {
        $this->attributes['to'] = $value ;
        // ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
}
