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
        'start_location',
        'end_location',
        'required_date',
        'required_time',
        'preferred_sex',
        'worker_id',
    ];
    public function jobServiceCat()
    {
        return $this->hasMany(Job_Service_Cat::class, 'job_id', 'id');
    }
    public function workerFeedback()
    {
        return $this->hasMany(worker_feedback::class, 'job_id', 'id');
    }
}
