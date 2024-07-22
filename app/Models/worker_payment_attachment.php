<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class worker_payment_attachment extends Model
{
    use HasFactory;
    protected $table = 'worker_payment_attachment';
    protected $fillable = ['worker_payment_id', 'file_path'];

    public function worker_payment()
    {
        return $this->belongsTo(worker_payment::class, 'worker_payment_id', 'id');
    }
}
