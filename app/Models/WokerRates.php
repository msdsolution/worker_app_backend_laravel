<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WokerRates extends Model
{
    use HasFactory;

    protected $table = 'worker_rates';

    protected $fillable = [
        'amount',
        'day',
    ];
}
