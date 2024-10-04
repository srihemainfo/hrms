<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class professional_activities extends Model
{
    use HasFactory,SoftDeletes,Auditable;
    public $table = 'professional_activities';
    protected $dates = [

        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $fillable = [
        'winning_in_competitions',
        'participation_in_competitions',
        'participation_in_co_curricular_activates',
        'participation_in_extra_curricular_activates',
        'leader_board_score',
        'user_name_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public function user_name()
    {
        return $this->belongsTo(User::class, 'user_name_id');
    }
}
