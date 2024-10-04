<?php

namespace App\Models;

use Google\Service\Forms\Feedback;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback_questions extends Model
{
    use SoftDeletes, HasFactory;
    public $table = 'feedback_questions';

    protected $guarded;
    
    public function feedback()
    {
        return $this->belongsTo(Feedback::class, 'feedback_id');
    }
}
