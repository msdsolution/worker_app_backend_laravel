<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class extended_hour extends Model
{
    use HasFactory;
    protected $table = 'extended_hour';

    protected $fillable = [
        'hour_extended',
        'amount',
    ];
}
