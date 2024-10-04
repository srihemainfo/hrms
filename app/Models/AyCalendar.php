<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AyCalendar extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'college_calenders_preview';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'start_date',
        'end_date',
        'date',
        'dayorder',
        'bookmark',
        'created_at',
        'updated_at',
        'deleted_at',
        'semester_type',
        'academic_year',
        'batch',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
