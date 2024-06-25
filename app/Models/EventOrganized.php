<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventOrganized extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'event_organized';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'event_type',
        'title',
        'funding_support',
        'coordinated_sjfc',
        'audience_category',
        'participants',
        'event_duration',
        'start_date',
        'end_date',
        'status',
        'chiefguest_information',
        'total_participants',
        'certificate',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
