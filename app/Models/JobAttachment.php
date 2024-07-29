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

    public function getImgUrlAttribute()
    {
        return $this->attributes['img_url']? url('storage/' . $this->attributes['img_url']) : null;
    }

}
