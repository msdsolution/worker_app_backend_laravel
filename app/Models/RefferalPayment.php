<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Prompts\Table;

class RefferalPayment extends Model
{
    use HasFactory;
    protected $table = 'refferal_payment';
    protected $fillable = ['job_id', 'amount', 'status','date_time_transaction','payment_gateway_used','status_code','comment'];

}
