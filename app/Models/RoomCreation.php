<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomCreation extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'rooms';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'block_id',
        'room_no',
        'no_of_class_seats',
        'no_of_exam_seats',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function block()
    {
        return $this->belongsTo(CollegeBlock::class, 'block_id');
    }
}
