<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SriLankaProvinces extends Model
{
    use HasFactory;

     protected $table = 'provinces';

    protected $fillable = [
        'name_en',
        'name_si',
        'name_ta',
    ];

     public function getDistricts()
    {
        return $this->hasMany(SriLankaDistricts::class, 'province_id');
    }
}
