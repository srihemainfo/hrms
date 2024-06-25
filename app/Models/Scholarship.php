<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scholarship extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'scholarships';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'foundation_name',
        'remarks',
        'started_ay',
        'started_batch',
        'status',
        'inactive_reason',
        'inactive_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function theAys()
    {
        return $this->belongsTo(AcademicYear::class, 'started_ay');
    }
    public function theBatches()
    {
        return $this->belongsTo(Batch::class, 'started_batch');
    }
}
