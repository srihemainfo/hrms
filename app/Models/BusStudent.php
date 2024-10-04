<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusStudent extends Model
{
    use HasFactory, SoftDeletes;
    public $table = "bus_student";

    protected $guarded;
}
