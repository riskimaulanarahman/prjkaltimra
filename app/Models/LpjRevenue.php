<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lpj;

class LpjRevenue extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function lpj()
    {
        return $this->hasOne(Lpj::class, 'id', 'lpj_id');
    }
}
