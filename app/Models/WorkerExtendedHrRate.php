<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Prompts\Table;

class WorkerExtendedHrRate extends Model
{
    use HasFactory;
    protected $table = 'worker_extended_hr_rate';
    protected $fillable = ['hr_extended', 'amount'];
}
