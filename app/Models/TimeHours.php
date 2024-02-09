<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeHours extends Model
{
    use HasFactory;

    protected $table = 'time_hours';

    protected $fillable = [
        'name',
    ];
}
