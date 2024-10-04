<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostelBlock extends Model
{
    use HasFactory;
    public $table = 'hostel_block';

    protected $guarded;

    public function hostelRoom()
    {
        return $this->hasMany(HostelRoom::class, 'hostel_id');
    }
    public function roomAllot()
    {
        return $this->hasMany(RoomAllocationModel::class, 'hostel_id');
    }
}
