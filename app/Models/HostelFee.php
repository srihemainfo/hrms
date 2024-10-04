<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HostelFee extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'hostel_fee';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'std_user_name_id',
        'register_number',
        'name',
        'hostel_block_id',
        'batch_id',
        'amount',
        'academic_year_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function hostel_block()
    {
        return $this->belongsTo(HostelBlock::class, 'hostel_block_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function ay()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

}
