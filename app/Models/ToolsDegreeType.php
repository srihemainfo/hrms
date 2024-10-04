<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToolsDegreeType extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'tools_degree_types';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function degreetypeCourseEnrollMasters()
    {
        return $this->hasMany(CourseEnrollMaster::class, 'degreetype_id', 'id');
    }
}
