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
    ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     // Creating event to set job number
    //     static::created(function ($job) {
    //         // length of the job ID
    //         $jobIdLength = strlen($job->id);
            
    //         // Generating job number in format #00000 with job ID appended
    //         $jobNumber = '#' . str_pad($job->id, $jobIdLength + 1, '0', STR_PAD_LEFT);
            
    //         $job->job_no = $jobNumber;
    //     });

    //     // Validation only on creating new jobs
    //     static::saving(function ($job) {
    //         if (!$job->exists) {
    //             $validator = Validator::make(['id' => $job->id], ['job_no' => 'unique:jobs,job_no']);
                
    //             if ($validator->fails()) {
    //                 // You can also return false or handle the validation error as per your application's logic.
    //                 throw new \Exception('Job number must be unique.');
    //             }
    //         }
    //     });
    // }

    public function jobType()
    {
        return $this->hasMany(Job_Service_Cat::class, 'job_id');
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
