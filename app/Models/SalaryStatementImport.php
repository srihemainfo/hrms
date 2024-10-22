<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryStatementImport extends Model
{
    use HasFactory, SoftDeletes;

    public $table = "salary_statements_import";

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'doj',
        'working_days',
        'lop_days',
        'ot',
        'salaryadvance',
        'one_hour_late',
        'two_hour_late',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
