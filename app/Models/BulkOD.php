<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BulkOD extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'bulk_od';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'organized_by',
        'dept_name',
        'incharge',
        'event_title',
        'event_category',
        'from_date',
        'to_date',
        'register_no',
        'academic_year',
        'dept',
        'course',
        'semester',
        'section',
        'ext_event_venue',
        'user_name_id',
        'rejected_reason',
        'duration',
        'from_period',
        'status',
        'to_period',
        'document',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_name_id', 'user_name_id');
    }

    public function tech_staff()
    {
        return $this->belongsTo(TeachingStaff::class, 'incharge', 'user_name_id');
    }
}
