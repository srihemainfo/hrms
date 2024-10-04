<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectMaster extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'subjects';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'Subject_Name',
        'Regulation',
        'Semester',
        'Course',
        'Department',
        'Subject_Code',
        'Subject_Type',
        'Subject_Category',
        'Lecture',
        'Tutorial',
        'Practical',
        'Contact_Periods',
        'Credits',
    ];

}
