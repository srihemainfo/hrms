<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventParticipation extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'event_participations';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'event_category',
        'event_type',
        'title',
        'organized_by',
        'event_location',
        'event_duration',
        'start_date',
        'end_date',
        'certificate',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
