<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    use HasFactory;
    public $table = 'fee_structure';

    protected $guarded;

    public function courses()
    {
        return $this->belongsTo(ToolsCourse::class, 'course_id');
    }

    public function admissions()
    {
        return $this->belongsTo(AdmissionMode::class, 'admission_id');
    }
}
