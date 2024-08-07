<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OverAllFeedbacksModel extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'overall_feedbacks';

    protected $guarded;
    public function feedback()
    {
        return $this->belongsTo(Feedback::class, 'feedback_id');
    }
    public function feedback_schedule()
    {
        return $this->belongsTo(FeedbackSchedule::class, 'feed_schedule_id', 'id');
    }
    public function teaching()
    {
        return $this->belongsTo(TeachingStaff::class, 'staff_id', 'user_name_id');
    }

}
