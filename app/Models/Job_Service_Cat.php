<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job_Service_Cat extends Model
{
    use HasFactory;

    protected $table = 'job_service_cat';

    protected $fillable = [
        'job_id',
        'service_cat_id',
        'refferal_rate_id',
        'refferal_amount',
        'woker_rate_id',
        'worker_amount',
    ];
}
