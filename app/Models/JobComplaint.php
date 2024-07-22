<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobComplaint extends Model
{
    use HasFactory;
    protected $table = 'job_complaint';

    protected $fillable = [
        'job_id',
        'status',
    ];

    public function complaintMessages()
    {
        return $this->hasMany(ComplaintMessage::class, 'complaint_id', 'id');
    }
}
