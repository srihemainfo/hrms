<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassRoom extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'class_rooms';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'block_id',
        'type',
        'room_no',
        'short_form',
        'department_id',
        'class_incharge',
        'no_of_seat',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function room()
    {
        return $this->belongsTo(RoomCreation::class, 'room_no');
    }
    public function selected_room()
    {
        return $this->belongsTo(RoomCreation::class, ['block_id','room_no']);
    }

    public function block()
    {
        return $this->belongsTo(CollegeBlock::class, 'block_id');
    }

    public function enroll_master()
    {
        return $this->belongsTo(CourseEnrollMaster::class, 'name');
    }

    public function department()
    {
        return $this->belongsTo(ToolsDepartment::class, 'department_id');
    }

    public function teaching_staff()
    {
        return $this->belongsTo(User::class, 'class_incharge');
    }
}
