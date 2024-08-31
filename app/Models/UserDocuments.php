<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserDocuments extends Model
{
    use HasFactory;
    protected $table = 'user_documents';

    protected $fillable = [
        'id',
        'user_id',
        'doc_id',
        'doc_url',
    ];

    // Accessor for user_doc_url
    public function getDocUrlAttribute()
    {
        return $this->attributes['doc_url'] ? url('storage/' . $this->attributes['doc_url']) : null;
    }

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'id');
    // }

}
