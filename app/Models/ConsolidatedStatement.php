<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsolidatedStatement extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'consolidated_statements';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'batch',
        'course',
        'regulation',
        'file_path',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getRegulation()
    {
        return $this->belongsTo(ToolssyllabusYear::class, 'regulation');
    }

    public function getBatch()
    {
        return $this->belongsTo(Batch::class, 'batch');
    }

    public function getCourse()
    {
        return $this->belongsTo(ToolsCourse::class, 'course');
    }
}
