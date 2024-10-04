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

    public function teaching()
    {
        return $this->hasMany(TeachingStaff::class, 'id', 'shift_id');

    }
    public function student()
    {
        return $this->hasMany(Student::class, 'id', 'shift_id');

    }
}
