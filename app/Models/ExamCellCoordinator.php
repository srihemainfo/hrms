<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamCellCoordinator extends Model
{
    use SoftDeletes,Auditable,HasFactory;

    public $table = 'exam_cell_coordinators';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'exam_type',
        'exam_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
