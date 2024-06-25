<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomAllocationModel extends Model
{
    use HasFactory;

    public $table = 'room_allocation';

    protected $guarded;

    public function hostelRoom()
    {
        return $this->belongsTo(HostelRoom::class);
    }
    public function hostelName()
    {
        return $this->hasOne(HostelBlock::class, 'hostel_id');
    }
}
