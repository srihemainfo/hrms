<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RemovedStudent extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'removed_students';

    protected $fillable = [
        'user_name_id',
        'name',
        'register_no',
        'enroll_master',
        'reason',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function enroll()
    {
        return $this->belongsTo(CourseEnrollMaster::class, 'enroll_master');
    }
}
