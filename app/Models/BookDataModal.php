<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookDataModal extends Model
{
    use HasFactory, SoftDeletes;
    public $table = "book_data";
    protected $guarded;

    public function books()
    {
        return $this->belongsTo(BookModel::class, 'book_id');
    }
}
