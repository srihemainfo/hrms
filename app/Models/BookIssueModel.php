<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookIssueModel extends Model
{
    use HasFactory, SoftDeletes;

    public $table = "book_issue";

    protected $guarded;

    public function books(){
        return $this->belongsTo(BookModel::class, 'book_id');
    }
    public function bookData(){
        return $this->belongsTo(BookDataModal::class, 'book_data_id');
    }
    public function users(){
        return $this->belongsTo(User::class, 'user_name_id');
    }

}
