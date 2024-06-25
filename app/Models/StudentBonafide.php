<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentBonafide extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'student_bonafides';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'bonafide_type',
        'created_at',
        'updated_at',
        'deleted_at',
        
    ];
}
