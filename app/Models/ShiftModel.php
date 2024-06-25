<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShiftModel extends Model
{
    use HasFactory, SoftDeletes;
    public $table = 'shift';

    protected $guarded;
}
