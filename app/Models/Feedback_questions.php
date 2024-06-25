<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback_questions extends Model
{
    use SoftDeletes, HasFactory;
    public $table = 'feedback_questions';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'category',
        'questions',
        'answertype',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
