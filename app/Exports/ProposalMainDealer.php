<?php

namespace App\Exports;

use App\Models\Proposal;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProposalMainDealer implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    public function status($status = null)
    { $this->status = $status; return $this; }

    public function kategori($kategori = null)
    { $this->kategori = $kategori; return $this; }

    public function area($area = null)
    { $this->area = $area; return $this; }

    // public function lokasi($lokasi)
    // { $this->lokasi = $lokasi; return $this; }

    public function dealer($dealer = null)
    { $this->dealer = $dealer; return $this; }

    public function bulan(String $bulan = null)
    { $this->bulan = $bulan; return $this; }

    public function tahun($tahun = null)
    { $this->tahun = $tahun; return $this; }

    public function tanggalst($tanggalst = null)
    { $this->tanggalst = $tanggalst; return $this; }

    public function tanggalen($tanggalen = null)
    { $this->tanggalen = $tanggalen; return $this; }

    public function type($type) // 1 - by date -- 2 = by month
    { $this->type = $type; return $this; }




    public function query()
    {
        return Proposal::query()
            ->when($this->status, function ($query) {
                if ($this->status != 'SEMUA') { $query->where('status_proposal', $this->status); }
            })
            ->when($this->kategori, function ($query) {
                if ($this->kategori != 'SEMUA') { $query->where('kategori_proposal', $this->kategori); }
            })
            ->when($this->dealer, function ($query) {
                if ($this->dealer != 'SEMUA') { $query->where('dealer_proposal', $this->dealer); }
            })
            ->when($this->area, function ($query) {
                if ($this->area != 'SEMUA') { $query->area($this->area); }
            })
            ->when($this->type, function ($query) {
                if ($this->type == 1) {
                    $query->whereBetween('periode_start_proposal', [$this->tanggalst, $this->tanggalen])
                          ->whereBetween('periode_end_proposal', [$this->tanggalst, $this->tanggalen]);
                } elseif ($this->type == 2 ) {
                    if($this->bulan == 'SEMUA'){
                        $query->whereYear('periode_start_proposal', $this->tahun);
                    } else {
                        $query->whereMonth('periode_start_proposal', $this->bulan)
                              ->whereYear('periode_start_proposal', $this->tahun);
                    }
                }
            });
    }

    public function headings() : array {
        return [
           'Nomor',
           'Status',
           'Jenis Proposal',
           'Start Date',
           'End Date',
           'Penanggung Jawab',
           'Area',
           'Dealer',
           'Tempat',
           'Lokasi',
           'Finance Company',
           'Target Database',
           'Target Penjualan',
           'Target Prospecting',
           'Target Downloader Motorku-X',
           'Total Perkiraan Dana',
           'Submit Date'
        ] ;
    }

    public function map($data) : array {
        return [
            $data->no_proposal ?? null,
            $data->statusp->nama_status ?? null,
            $data->kategori->nama_kategori ?? null,
            $data->periode_start_proposal ?? null,
            $data->periode_end_proposal ?? null,
            $data->pj->nama_sales_people ?? null,
            $data->lokasi->kota_lokasi ?? null,
            $data->dealer->nama_dealer ?? null,
            $data->tempat_proposal ?? null,
            $data->finance_proposal ?? null,
            $data->lokasi->kelurahan_lokasi ?? null.', '.$data->lokasi->kecamatan_lokasi ?? null,
            $data->target_database_proposal ?? null,
            $data->target_penjualan_proposal ?? null,
            $data->target_prospectus_proposal ?? null,
            $data->target_download_proposal ?? null,
            $data->total_dana_proposal ?? null,
            $data->submit_date ?? null,
        ];
    }

}
