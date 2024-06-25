<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradeMaster extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'grade_master';

    protected $fillable = [
        'regulation_id',
        'grade_letter',
        'grade_point',
        'result',
        'grade_sheet_show',
        'grade_book_show',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function regulations()
    {
        return $this->belongsTo(ToolssyllabusYear::class, 'regulation_id');
    }
}
