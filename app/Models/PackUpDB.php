<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackUpDB extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'packup_db';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'ay',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getAy()
    {
        return $this->belongsTo(AcademicYear::class, 'ay');
    }
}
