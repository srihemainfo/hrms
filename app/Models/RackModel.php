<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RackModel extends Model
{
    use HasFactory, SoftDeletes;
    public $table = "rack";
    protected $guarded;

    public function bookAllocate()
    {
        return $this->belongsTo(BookModel::class, 'row_id');
    }
}
