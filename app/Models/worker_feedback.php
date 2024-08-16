<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class worker_feedback extends Model
{
    use HasFactory;
    protected $table = 'worker_feedback';

    protected $fillable = [
        'job_id',
        'message',
        'ratings',
        'refferal_id',
        'status',
        'user_id',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function refferalUser()
    {
        return $this->belongsTo(User::class, 'id', 'refferal_id');
    }
}
