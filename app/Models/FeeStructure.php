<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeStructure extends Model
{
    use SoftDeletes, Auditable, HasFactory;
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

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

}
