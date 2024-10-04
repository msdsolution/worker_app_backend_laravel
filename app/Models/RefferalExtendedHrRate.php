<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Prompts\Table;

class RefferalExtendedHrRate extends Model
{
    use HasFactory;
    protected $table = 'refferal_extended_hr_rate';
    protected $fillable = ['hr_extended', 'amount'];
}
