<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalProposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_proposal',
        'id_lpj', // ALTER TABLE `additional_proposals` ADD `id_lpj` INT(11) NULL DEFAULT NULL AFTER `id_proposal`;
        'kpb_1',
        'kpb_2',
        'kpb_3',
        'kpb_4',
        'psl',
        'psr',
        'go',
        'lr',
        'jasa',
        'part',
        'oli',
    ];
}
