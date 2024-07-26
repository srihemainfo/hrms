<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralFeedbackModel extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'general_feedback';

    protected $guarded;

    public function feedback()
    {
        return $this->belongsTo(Feedback::class, 'feedback_id');
    }
}
