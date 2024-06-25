<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhdDetail extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'phd_details';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'institute_name',
        'university_name',
        'thesis_title',
        'research_area',
        'supervisor_name',
        'supervisor_details',
        'status',
        'registration_year',
        'viva_date',
        'total_years',
        'mode',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

}
