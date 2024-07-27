<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAttachment extends Model
{
    use HasFactory;
    protected $table = 'job_attachments';

    protected $fillable = [
        'img_url',
        'job_id',
    ];

}
