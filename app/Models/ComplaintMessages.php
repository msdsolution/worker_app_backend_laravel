<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintMessages extends Model
{
    use HasFactory;
    protected $table = 'complaint_messages';

    protected $fillable = [
        'complaint_id',
        'user_id',
        'message',
    ];

    public function jobComplaint()
    {
        return $this->belongsTo(JobComplaint::class, 'complaint_id', 'id');
    }
}
