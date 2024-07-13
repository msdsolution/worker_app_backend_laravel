<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintAttachment extends Model
{
    use HasFactory;
    protected $table = 'complaint_attachments';

    protected $fillable = [
        'img_url',
        'complaint_id',
        'complaint_message_id',
    ];

    public function message()
    {
        return $this->belongsTo(ComplaintMessages::class, 'complaint_message_id');
    }

}
