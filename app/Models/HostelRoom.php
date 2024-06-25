<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostelRoom extends Model
{
    use HasFactory;
    public $table = 'hostel_room';

    protected $guarded;

    public function hostel()
    {
        return $this->belongsTo(HostelBlock::class);
    }
    public function roomAllot()
    {
        return $this->hasMany(RoomAllocationModel::class);
    }
}
