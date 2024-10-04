<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HostelWardenModel extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'hostel_warden';

    protected $guarded;

    public function non_teaching()
    {
        return $this->belongsTo(NonTeachingStaff::class, 'warden_id', 'user_name_id');
    }
    public function hostel()
    {
        return $this->belongsTo(HostelBlock::class, 'hostel_id');
    }

}
