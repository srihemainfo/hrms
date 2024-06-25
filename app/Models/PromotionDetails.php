<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromotionDetails extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'promotion_details';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'current_designation',
        'promoted_designation',
        'promotion_date',
    ];

}
