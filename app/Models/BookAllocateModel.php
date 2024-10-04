<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookAllocateModel extends Model
{
    use HasFactory, SoftDeletes;
    public $table = "book_allocation";
    protected $guarded;

    public function rack(){
        return $this->belongsTo(RackModel::class, 'row_id');
    }
    public function bookData(){
        return $this->belongsTo(BookDataModal::class, 'book_data_id');
    }
}
