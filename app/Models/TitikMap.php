<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dealer;

class TitikMap extends Model
{
    use HasFactory;

    protected $fillable = [
       'kota',
       'latitude',
       'longtitude',
       'zoom',
       'icon',
       'color',
       'radius',
       'keterangan',
    ];

    public function dealer()
    {
        return $this->hasMany(Dealer::class, 'kota_dealer', 'dealer');
    }
}
