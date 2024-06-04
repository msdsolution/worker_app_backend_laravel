<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cities extends Model
{
    use HasFactory;
    protected $table = 'cities';

    protected $fillable = [
        'name_en',
        'name_si', // Add name_si attribute to fillable
        'name_ta', // Add name_ta attribute to fillable
        // Add other fillable attributes if needed
    ];
}
