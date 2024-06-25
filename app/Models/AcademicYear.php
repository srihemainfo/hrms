<?php

namespace App\Models;


use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYear extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'academic_years';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'from',
        'to',
        'name',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function booted()
    {
        // Define the default order for the model
        static::addGlobalScope('order', function ($builder) {
            $builder->orderBy('created_at', 'desc');
        });
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function academicCourseEnrollMasters()
    {
        return $this->hasMany(CourseEnrollMaster::class, 'academic_id', 'id');
    }
}
