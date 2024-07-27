<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefferalRates extends Model
{
    use HasFactory;
    
    protected $table = 'refferal_rates';

    protected $fillable = [
    	'id',
        'amount',
        'day',
    ];
}
