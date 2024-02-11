<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SriLankaDistricts extends Model
{
    use HasFactory;

     protected $table = 'districts';

    protected $fillable = [
        'name_en',
        'name_si',
        'name_ta',
    ];

    public function cities()
    {
        return $this->hasMany(SriLankaCities::class, 'district_id')->select('id','district_id','name_en','name_si','name_ta');
    }
}
