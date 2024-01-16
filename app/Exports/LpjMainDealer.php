<?php

namespace App\Exports;

use App\Models\Lpj;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LpjMainDealer implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
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
        return Lpj::query()
            ->leftJoin('proposals','lpjs.id_proposal','=','proposals.id')
            ->where('status_lpj','!=',1)
            ->when($this->status, function ($query) {
                if ($this->status != 'SEMUA') { 
                    $query->where('status_lpj', $this->status);
                }
            })
            ->when($this->kategori, function ($query) {
                if ($this->kategori != 'SEMUA') { 
                    $query->where('proposals.kategori_proposal', $this->kategori);
                }
            })
            ->when($this->dealer, function ($query) {
                if ($this->dealer != 'SEMUA') { $query->where('proposals.dealer_proposal', $this->dealer); }
            })
            ->when($this->area, function ($query) {
                if ($this->area != 'SEMUA') { $query->area($this->area); }
            })
            ->when($this->type, function ($query) {
                if ($this->type == 1) {
                    $query->whereBetween('periode_start_lpj', [$this->tanggalst, $this->tanggalen])
                          ->whereBetween('periode_end_lpj', [$this->tanggalst, $this->tanggalen]);
                } elseif ($this->type == 2 ) {
                    if($this->bulan == 'SEMUA'){
                        $query->whereYear('periode_start_lpj', $this->tahun);
                    } else {
                        $query->whereMonth('periode_start_lpj', $this->bulan)
                              ->whereYear('periode_start_lpj', $this->tahun);
                    }
                }
            });
    }

    public function headings() : array {
        return [
        //    'Nomor',
           'Status',
           'Start Date',
           'End Date',
           'Proposal',
           'Dealer',
           'Lokasi',
        //    'Penanggung Jawab',
           //    'Area',
        //    'Tempat',
        //    'Finance Company',
           'Database',
           'Prospecting',
           'Penjualan',
           'Biaya',
        //    'Target Downloader Motorku-X',
        //    'Total Perkiraan Dana',
           'Submit Date'
        ] ;
    }

    public function map($data) : array {
        $status_lpj_options = [
            1 => 'DRAFT',
            2 => 'Approved',
            3 => 'CANCEL',
            4 => 'Waiting Approval',
            5 => 'Reject',
            6 => 'Revisi',
        ];
        return [
            // $data->no_proposal ?? null,
            $status_lpj_options[$data->status_lpj] ?? null,
            // $data->statusp->nama_status ?? null,
            $data->periode_start_lpj ?? null,
            $data->periode_end_lpj ?? null,
            $data->proposal->kategori->nama_kategori ?? null,
            $data->proposal->dealer->nama_dealer ?? null,
            $data->lokasi->kota_lokasi.','.$data->lokasi->kecamatan_lokasi.','.$data->lokasi->kelurahan_lokasi ?? null,
            // $data->proposal->pj->nama_sales_people ?? null,
            // $data->tempat_proposal ?? null,
            // $data->finance_proposal ?? null,
            // $data->lokasi->kelurahan_lokasi ?? null.', '.$data->lokasi->kecamatan_lokasi ?? null,
            $data->target_database_lpj ?? null,
            $data->target_prospectus_lpj ?? null,
            $data->target_penjualan_lpj ?? null,
            // $data->target_download_proposal ?? null,
            $data->total_dana_lpj ?? null,
            $data->submit_date ?? null,
        ];
    }

}
