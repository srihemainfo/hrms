<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntranceExam extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'entrance_exams';

    protected $dates = [
        'passing_year',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name_id',
        'exam_type_id',
        'passing_year',
        'scored_mark',
        'total_mark',
        'rank',
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

    public function exam_type()
    {
        return $this->belongsTo(Examstaff::class, 'exam_type_id');
    }

    public function getPassingYearAttribute($value)
    {
        // return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
        return $value;
    }

    public function setPassingYearAttribute($value)
    {
        // $this->attributes['passing_year'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
        $this->attributes['passing_year'] = $value;
    }
}
