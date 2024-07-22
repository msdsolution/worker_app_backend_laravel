<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Prompts\Table;

class worker_payment extends Model
{
    use HasFactory;
    protected $table = 'worker_payment';
    protected $fillable = ['job_id', 'amount', 'status'];

    public function worker_payment_attachment()
    {
        return $this->hasMany(worker_payment_attachment::class, 'worker_payment_id', 'id');
    }
}
