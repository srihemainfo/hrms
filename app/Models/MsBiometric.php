<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsBiometric extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'AttendanceLogs';

    protected $guarded;
}
