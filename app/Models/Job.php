<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Job extends Model 
{
    use HasFactory;

    protected $table = 'job';

    protected $fillable = [
        'íd',
        'description',
        'city_id',
        'start_location',
        'end_location',
        'required_date',
        'required_time',
        'preferred_sex',
        'worker_id',
        'user_id',
        'job_no',
        'is_extended',
        'extended_hrs',
    ];

    public function jobType()
    {
        return $this->hasMany(Job_Service_Cat::class, 'job_id');
    }

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function complaint()
    {
        return $this->hasOne(JobComplaint::class, 'job_id');
    }

    //  public function jobServices()
    // {
    //     return $this->belongsToMany(Job_Service_Cat::class, 'job_service_cat', 'job_id', 'id');
    // }

    public function jobServiceCat()
    {
        return $this->hasMany(Job_Service_Cat::class, 'job_id', 'id');
    }
    
    public function workerFeedback()
    {
        return $this->hasMany(worker_feedback::class, 'job_id', 'id');
    }
}
