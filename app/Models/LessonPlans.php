<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LessonPlans extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'lesson_plans';

    protected $fillable = [
        'user_name_id',
        'class',
        'subject',
        'unit',
        'unit_no',
        'topic',
        'topic_no',
        'text_book',
        'delivery_method',
        'proposed_date',
        'status',
        'rejected_reason',
        'approved_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
