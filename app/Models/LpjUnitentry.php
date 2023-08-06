<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lpj;

class LpjUnitentry extends Model
{
    use HasFactory;

    protected $table = 'lpj_unitentrys';

    protected $guarded = ['id'];

    public function lpj()
    {
        return $this->hasOne(Lpj::class, 'id', 'proposal_id');
    }
}
