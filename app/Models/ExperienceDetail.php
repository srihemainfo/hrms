<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExperienceDetail extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'experience_details';

    protected $dates = [
        'from_date',
        'to_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'designation',
        'department',
        'name_of_organisation',
        'last_drawn_salary',
        'taken_subjects',
        'doj',
        'dor',
        'responsibilities',
        'leaving_reason',
        'address',
        'user_name_id',
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
}
