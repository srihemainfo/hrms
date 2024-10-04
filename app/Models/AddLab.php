<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AddLab extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'add_labs';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'dept',
        'lab_name',
        'lab_incharge',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function block()
    {
        return $this->belongsTo(CollegeBlock::class, 'block_id');
    }

    public function department()
    {
        return $this->belongsTo(ToolsDepartment::class, 'dept');
    }

    public function teaching_staff()
    {
        return $this->belongsTo(TeachingStaff::class, 'lab_incharge');
    }
}
