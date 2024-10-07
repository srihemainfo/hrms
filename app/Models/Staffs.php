<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Staffs extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'staffs';
    protected $guarded;
}
