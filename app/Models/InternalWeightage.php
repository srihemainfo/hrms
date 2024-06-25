<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternalWeightage extends Model
{
    use HasFactory,SoftDeletes;

    public $table = 'internal_weightage';

    protected $fillable = [
        'regulation',
        'academic_year',
        'subject_type',
        'category',
        'semester',
        'internal_weightage',
        'total',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getRegulation()
    {
        return $this->belongsTo(ToolssyllabusYear::class, 'regulation');
    }

    public function getAy()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year');
    }

    public function getSubType()
    {
        return $this->belongsTo(SubjectType::class, 'subject_type');
    }
}
