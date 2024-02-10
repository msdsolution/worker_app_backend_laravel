<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client_rate extends Model
{
    use HasFactory;
    protected $table = 'refferal_rates';

    protected $fillable = [
        'amount',
        'day',
    ];
}
