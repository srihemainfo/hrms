<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditLimitMaster extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'credit_limit_master';

    protected $fillable = [
        'regulation_id',
        'credit_limit',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function regulations()
    {
        return $this->belongsTo(ToolssyllabusYear::class, 'regulation_id');
    }
}
