<?php

namespace App\Models;


use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddConference extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'add_conferences';

    protected $dates = [
        'conference_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'topic_name',
        'location',
        'conference_date',
        'contribution_of_conference',
        'project_name',
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

    public function getConferenceDateAttribute($value)
    {
        return $value;
    }

    public function setConferenceDateAttribute($value)
    {
        $this->attributes['conference_date'] = $value;
    }
}
