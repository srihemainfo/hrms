<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class salarystatement extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'salarystatements';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'name',
        'month',
        'year',
        'department',
        'doj',
        'total_working_days',
        'designation',
        'total_payable_days',
        'total_lop_days',
        'chequeno',
        'bankname',
        'basicpay',
        'agp',
        'da',
        'hra',
        'conveyance',
        'specialpay',
        'arrears',
        'otherall',
        'abi',
        'phdallowance',
        'earnings',
        'gross_salary',
        'it',
        'pt',
        'salaryadvance',
        'esi',
        'epf',
        'lop',
        'otherdeduction',
        'totaldeductions',
        'netpay',
        'updatedby',
    ];

    public function user_name()
    {
        return $this->belongsTo(User::class, 'user_name_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_name_id');
    }
}
