<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KategoriProposal;
use App\Models\StatusProposal;
use App\Models\Lokasi;
use App\Models\Karyawan;
use App\Models\Display;
use App\Models\Cabang;
use App\Models\Dealer;
use App\Models\SalesPeople;
USE App\Models\Pusat;
use App\Models\Lpj;
use App\Models\AdditionalProposal;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'no_proposal',
        'user_proposal',
        'user_approval',
        'create_proposal',
        'inbox_d',
        'inbox_md',
        'status_proposal',
        'status_event_proposal',
        'dealer_proposal',
        'lokasi_proposal',
        'alamat_proposal',
        'display_proposal',
        'finance_proposal',
        'target_database_proposal',
        'target_penjualan_proposal',
        'target_prospectus_proposal',
        'target_downloader_proposal',
        'periode_start_proposal',
        'periode_end_proposal',
        'program_proposal',
        'lat_proposal',
        'long_proposal',
        'keterangan_proposal',
        'foto_lokasi_proposal',
        'dana_proposal',
        'total_dana_proposal',
        'penanggung_jawab_proposal',
        'sales_people_proposal',
        'history_penjualan_proposal',
        'tanggal_status_proposal',
        'kategori_proposal',
        'latar_belakang_proposal',
        'latar_kompetitor_proposal',
        'kondisi_penjualan_m_1_proposal',
        'kondisi_penjualan_m1_proposal',
        'tujuan_proposal',
        'tempat_proposal',
        'unit_entry_proposal',
        'total_unit_entry_proposal',
        'revenue_proposal',
        'total_revenue_proposal',
    ];

    public function dealer()
    {
        return $this->hasOne(Dealer::class, 'id', 'dealer_proposal');
    }

    public function kategori()
    {
        return $this->hasOne(KategoriProposal::class, 'id', 'kategori_proposal');
    }

    public function statusp()
    {
        return $this->hasOne(StatusProposal::class, 'id', 'status_proposal');
    }

    public function lokasi()
    {
        return $this->hasOne(Lokasi::class, 'id', 'lokasi_proposal');
    }

    public function display()
    {
        return $this->hasMany(Display::class, 'id', 'display_proposal');
    }

    public function pj()
    {
        return $this->hasOne(SalesPeople::class, 'id', 'penanggung_jawab_proposal');
    }

    public function sales()
    {
        return $this->hasOne(SalesPeople::class, 'id', 'sales_people_proposal');
    }

    public function lpj()
    {
        return $this->hasMany(Lpj::class, 'id_proposal', 'id');
    }

    public function lpj_()
    {
        return $this->hasOne(Lpj::class, 'id_proposal', 'id');
    }

    public function additional()
    {
        return $this->hasOne(AdditionalProposal::class, 'id_proposal', 'id');
    }

    public function lpj_lead($startdate = null, $enddate = null)
    {
        // $this->tahun_a = $tahun;
        // $this->tahun_b = substr($waktu, 0, 4);
        // $this->bulan_b = substr($waktu, 5, 7);
        // $this->tanggal_a = $tanggal;
        // $this->waktu = $waktu;

        $this->startdate = $startdate;
        $this->enddate = $enddate;

        return $this->hasOne(Lpj::class, 'id_proposal', 'id')
                            ->leftJoin('proposals','lpjs.id_proposal','proposals.id')
                            ->leftJoin('lpj_konsumens','lpjs.id','lpj_konsumens.id_lpj')
                            ->where('status_lpj', 2)
                            ->where('proposals.status_proposal',4)
                            ->whereBetween('proposals.periode_start_proposal', [$this->startdate, $this->enddate])
                            ->whereBetween('proposals.periode_end_proposal', [$this->startdate, $this->enddate])
                            ->whereHas('konsumen', function ($query) {
                                return $query->where('hasil', 4);
                            })
                            // ->groupBy('lpj_konsumens.id')
                            ;
    }

    public function usercabang()
    {
        return $this->hasOne(Cabang::class, 'id', 'user_proposal');
    }

    public function userpusat()
    {
        return $this->hasOne(Pusat::class, 'id', 'user_proposal');
    }

    public function scopePj($query, $pj)
    {
        $this->pj = $pj;
        if ($this->pj) {
            return $query->whereHas('pj', function ($query) {
                return $query->whereRaw('LOWER(nama_sales_people) LIKE ? ', '%'.strtolower($this->pj).'%');
            });
        }
    }

    public function scopeArea($query, $area)
    {
        $this->area = $area;
        if ($this->area != 'SEMUA') {
            return $query->whereHas('dealer', function ($query) {
                return $query->whereRaw('LOWER(kota_dealer) LIKE ? ', '%'.strtolower($this->area).'%');
            });
        }
    }

    public function scopeKategori($query, $kategori)
    {
        if (!null == $kategori && $kategori != 'SEMUA') {
            return $query->where('kategori_proposal', $kategori);
        }
    }

    public function scopeLokasi($query, $lokasi)
    {
        if (!null == $lokasi && $lokasi != 'SEMUA') {
            return $query->where('lokasi_proposal', $lokasi);
        }
    }

    public function scopeStatusProposal($query, $status)
    {
        if ($status) {
            return $query->where('status_proposal', $status);
        }
    }

    public function scopeTanggal($query, $tanggal)
    {
        if ($tanggal) {
            return $query->whereDate('submit_date', 'LIKE', '%'.$tanggal.'%');
        }
    }

    public function scopeTanggalrange($query, $startdate, $enddate)
    {
        if ($startdate && $enddate) {
            return $query->whereBetween('periode_start_proposal', [$startdate, $enddate])
            ->whereBetween('periode_end_proposal', [$startdate, $enddate]);
        }
    }

    public function scopeCariDealer($query, $dealer)
    {
        if ($dealer) {
            return $query->where('dealer_proposal', $dealer);
        }
    }

    public function scopeLeaderboardPenjualan($query)
    {
        return $query->sum('target_downloader_proposal');
    }

    public function scopeFinalProposal($query)
    {
        return $query->where('status_proposal', 4);
    }

    public function scopeJumlahDownloader($query)
    {
        return $query->sum('target_downloader_proposal');
    }

    public function scopeUnitentry()
    {
        $data = collect([
            ['id' => 1, 'nama' => 'KPB 1'],
            ['id' => 2, 'nama' => 'KPB 2'],
            ['id' => 3, 'nama' => 'KPB 3'],
            ['id' => 4, 'nama' => 'KPB 4'],
            ['id' => 5, 'nama' => 'PSL'],
            ['id' => 6, 'nama' => 'PSR'],
            ['id' => 7, 'nama' => 'GO+'],
            ['id' => 8, 'nama' => 'LR'],
        ]);

        return $data;
    }

    public function scopeUnitentry_($query, $ue)
    {
        if ($ue == 1) { return 'KPB 1';}
        elseif ($ue == 2) { return 'KPB 2';}
        elseif ($ue == 3) { return 'KPB 3';}
        elseif ($ue == 4) { return 'KPB 4';}
        elseif ($ue == 5) { return 'PSL';}
        elseif ($ue == 6) { return 'PSR';}
        elseif ($ue == 7) { return 'GO+';}
        elseif ($ue == 8) { return 'LR';}
        else { return '-'; }
    }

    public function scopeRevenue_($query, $re)
    {
        if ($re == 1) { return 'Jasa';}
        elseif ($re == 2) { return 'Part';}
        elseif ($re == 3) { return 'Oli';}
        else { return '-'; }
    }


}
