<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SriLankaCities extends Model
{
    use HasFactory;

    protected $table = 'cities';

    protected $fillable = [
        'name_en',
        'name_si',
        'name_ta',
    ];
}
