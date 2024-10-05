<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransferPaymetRefferal extends Model
{
    use HasFactory;
    protected $table = 'bank_transfers_payment_refferals';

    protected $fillable = [
        'job_id',
        'amount',
        'status',
        'attachment_url',
    ];
}
