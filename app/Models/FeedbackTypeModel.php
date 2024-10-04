<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedbackTypeModel extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'feedback_type';
    protected $guarded;
}
