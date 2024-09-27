<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job_Service_Cat extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'job_service_cat';


    protected $fillable = [
        'job_id',
        'service_cat_id',
        'refferal_rate_id',
        'refferal_amount',
        'worker_rate_id',
        'worker_amount',
    ];

    // public function job()
    // {
    //     return $this->belongsTo(Job::class);
    // }

    public function serviceCat()
    {
        return $this->belongsTo(Service_Category::class, 'service_cat_id', 'id')->withTrashed();
    }

    // Define the relationship with Job
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id', 'id');
    }
}
