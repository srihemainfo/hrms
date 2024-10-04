<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use HasFactory, SoftDeletes;
    public $table = 'feedback';

    protected $guarded;

    public function users()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function feedback_question()
    {
        return $this->hasMany(Feedback_questions::class, 'id', 'feedback_id');
    }
    public function feedback_schedule()
    {
        return $this->hasMany(FeedbackSchedule::class, 'id', 'feedback_id');
    }
}
