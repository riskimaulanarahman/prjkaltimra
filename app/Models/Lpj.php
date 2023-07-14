<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Proposal;
use App\Models\Lokasi;
use Illuminate\Support\Carbon;
use App\Models\LpjKonsumen;

class Lpj extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'id_proposal',
        'tempat_lpj',
        'periode_start_lp',
        'periode_end_lp',
        'target_database_lpj',
        'target_penjualan_lpj',
        'target_prospectus_lpj',
        'target_downloader_lpj',
        'finance_lpj',
        'unit_lpj',
        'dana_lpj',
        'total_dana_lpj',
        'status_lpj',
        'dokumentasi_lpj',
        'problem_identification_lpj',
        'corrective_action_lpj',
    ];

    public function proposal()
    {
        return $this->hasOne(Proposal::class, 'id', 'id_proposal');
    }

    public function lokasi()
    {
        return $this->hasOne(Lokasi::class, 'id', 'lokasi_lpj');
    }

    public function konsumen()
    {
        return $this->hasMany(LpjKonsumen::class, 'id_lpj', 'id');
    }


    public function scopeKategori($query, $kategori)
    {
        $this->kategori = $kategori;
        if ($this->kategori && $this->kategori != 'SEMUA') {
            return $query->whereHas('proposal', function ($query) {
                return $query->where('kategori_proposal', $this->kategori);
            });
        }
    }

    public function scopePj($query, $pj)
    {
        $this->pj = $pj;
        if ($this->pj) {
            return $query->whereHas('proposal', function ($query) {
                return $query->whereHas('pj', function ($query) {
                    return $query->whereRaw('LOWER(nama_sales_people) LIKE ? ', '%'.strtolower($this->pj).'%');
                });
            });
        }
    }

    public function scopeDealer($query, $dealer)
    {
        $this->dealer = $dealer;
        if ($this->dealer && $this->dealer != 'SEMUA') {
            return $query->whereHas('proposal', function ($query) {
                return $query->whereHas('dealer', function ($query) {
                    return $query->where('id', $this->dealer);
                });
            });
        }
    }

    public function scopeSubmitDate($query, $submitdate)
    {
        if ($submitdate) {
            return $query->where('created_at', 'LIKE', '%'.$submitdate.'%');
        }
    }

    public function scopeCariLokasi($query, $lokasi)
    {
        if (!null == $lokasi && $lokasi != 'SEMUA') {
            return $query->where('lokasi_lpj', $lokasi);
        }
    }

    public function scopeArea($query, $area)
    {
        $this->area = $area;
        if ($this->area != 'SEMUA') {
            return $query->whereHas('proposal', function ($query) {
                return $query->whereHas('dealer', function ($query) {
                    return $query->whereRaw('LOWER(kota_dealer) LIKE ? ', '%'.strtolower($this->area).'%');
                });
            });
        }
    }

    public function scopeDurasi($query, $start, $end)
    {
        $s = Carbon::parse($start);
        $e = Carbon::parse($end);
        return $s->diffInDays($e);
    }

}
