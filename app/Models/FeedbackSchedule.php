<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedbackSchedule extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'feedback_schedule';

    protected $guarded;

    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'id','feedback_id');
    }

    public function feedback_schedule()
    {
        return $this->belongsTo(FeedbackSchedule::class, 'feed_schedule_id');
    }
    public function overall_feedbacks()
    {
        return $this->belongsTo(OverAllFeedbacksModel::class, 'id','feed_schedule_id');
    }

}
