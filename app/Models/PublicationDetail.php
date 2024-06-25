<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PublicationDetail extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'publication_details';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'publication_type',
        'paper_title',
        'journal_name',
        'book_series_title',
        'publisher',
        'organized_by',
        'issn_no',
        'doi',
        'proceeding_name',
        'volume_no',
        'issue',
        'pages',
        'scopus',
        'scie',
        'esci',
        'ahci',
        'ugc',
        'others',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
