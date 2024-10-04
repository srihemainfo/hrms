<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentPromotionHistory extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'student_promotion_history';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'enroll_master_id',
        'promoted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user_name()
    {
        return $this->belongsTo(User::class, 'user_name_id');
    }

    public function enroll_master()
    {
        return $this->belongsTo(CourseEnrollMaster::class, 'enroll_master');
    }

}
