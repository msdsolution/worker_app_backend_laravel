<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service_Category extends Model
{
    use HasFactory;

    protected $table = 'service_cat';

    protected $fillable = [
        'name',
        'description',
    ];
    public function jobServiceCat()
    {
        return $this->hasMany(Job_Service_Cat::class, 'service_cat_id', 'id');
    }
}
