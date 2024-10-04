<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class cat_exam_edit_request extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'cat_exam_edit_requests';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'Exam_id',
        'exam_name',
        'Exam_date',
        'class_name',
        'Class_subject',
        'exam_staff_id',
        'date',
        'reason',
        'status'
        ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
