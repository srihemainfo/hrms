<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookModel extends Model
{
    use HasFactory, SoftDeletes;
    public $table = "book_details";
    protected $guarded;
    protected $fillable = [
        'name',
        'isbn',
        'genre',
        'author',
        'publication',
        'image',
        'book_count',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public function row()
    {
        return $this->belongsTo(RackModel::class, 'id');
    }
    public function bookData()
    {
        return $this->belongsTo(BookDataModal::class, 'id', 'book_id');
    }

}
