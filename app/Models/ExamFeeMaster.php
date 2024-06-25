<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamFeeMaster extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'exam_fee_master';

    protected $fillable = [
        'regulation_id',
        'subject_type_id',
        'fee',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function regulations()
    {
        return $this->belongsTo(ToolssyllabusYear::class, 'regulation_id');
    }
    public function subject_types()
    {
        return $this->belongsTo(SubjectType::class, 'subject_type_id');
    }
}
