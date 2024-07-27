<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintMessages extends Model
{
    use HasFactory;
    protected $table = 'complaint_messages';

    protected $fillable = [
        'job_id',
        'user_id',
        'message',
    ];

    public function jobComplaint()
    {
        return $this->belongsTo(Job::class, 'job_id', 'id');
    }

    public function attachments()
    {
        return $this->hasMany(ComplaintAttachment::class, 'complaint_message_id');
    }
}
