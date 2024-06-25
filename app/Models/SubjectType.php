<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectType extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'subject_types';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'regulation_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function regulation()
    {
        return $this->belongsTo(ToolssyllabusYear::class, 'regulation_id');
    }
}
