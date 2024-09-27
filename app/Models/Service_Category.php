<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service_Category extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'service_cat';

    protected $fillable = [
        'name',
        'description',
        'img_icon_url',
    ];

   
    public function jobServiceCat()
    {
        return $this->hasMany(Job_Service_Cat::class, 'service_cat_id', 'id');
    }
}
