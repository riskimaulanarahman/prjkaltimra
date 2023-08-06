<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pusat;

class ApprovalLpj extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_lpj',
        'user_approval',
        'status_approval',
        'keterangan_approval',
    ];

    public function userapp()
    {
        return $this->hasOne(Pusat::class, 'id', 'user_approval');
    }
}
