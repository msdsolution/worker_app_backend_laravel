<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model 
{
    use HasFactory;

    protected $table = 'job';

    protected $fillable = [
        'description',
        'city_id',
        'start_location',
        'end_location',
        'required_date',
        'required_time',
        'preferred_sex',
        'worker_id',
        'user_id',
    ];

    public function jobType()
    {
        return $this->hasMany(Job_Service_Cat::class, 'job_id');
    }

    //  public function jobServices()
    // {
    //     return $this->belongsToMany(Job_Service_Cat::class, 'job_service_cat', 'job_id', 'id');
    // }

}
