<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TakeAttentanceStudent extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'take_attentance_students';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'enroll_master_id',
        'period',
        'taken_from',
        'approved_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function enroll_master()
    {
        return $this->belongsTo(CourseEnrollMaster::class, 'enroll_master_id');
    }
}
