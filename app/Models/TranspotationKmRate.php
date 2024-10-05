<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Prompts\Table;

class TranspotationKmRate extends Model
{
    use HasFactory;
    protected $table = 'transpotation_km_rate';
    protected $fillable = ['km', 'amount'];
}
