<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Proposal;
use Illuminate\Support\Carbon;
USE Illuminate\Support\Facades\Auth;

class KategoriProposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kategori',
        'keteragan_kategori',
        'waktu_minimum',
        'batas_approval',
    ];


    public function proposal()
    {
        return $this->hasMany(Proposal::class, 'kategori_proposal', 'id');
    }

    public function finalactivity($idkategori, $areakota, $iddealer, $startdate = null, $enddate = null)
    {

        $this->startdate = $startdate;
        $this->enddate = $enddate;

        $this->areakota = $areakota;
        $this->iddealer = $iddealer;
        return $this->hasMany(Proposal::class, 'kategori_proposal', 'id')
                            ->where('status_proposal', 4)
                            ->where('kategori_proposal', $idkategori)
                            ->when($this->areakota, function ($query) {
                                if($this->areakota != 'SEMUA') {
                                    return $query->whereHas('dealer', function ($query) {
                                        return $query->whereRaw('LOWER(kota_dealer) LIKE ? ', '%'.strtolower($this->areakota).'%');
                                    });
                                }
                            })
                            ->when($this->iddealer, function ($query) {
                                if($this->iddealer != 'SEMUA') {
                                    return $query->whereHas('dealer', function ($query) {
                                        return $query->where('id', $this->iddealer);
                                    });
                                }
                            })
                            ->whereBetween('periode_start_proposal', [$this->startdate, $this->enddate])
                            ->whereBetween('periode_end_proposal', [$this->startdate, $this->enddate]);
    }

    public function finalactivity_data($idkategori)
    {
        return $this->hasMany(Proposal::class, 'kategori_proposal', 'id')->where('status_proposal', 4)->where('kategori_proposal', $idkategori);
    }

    public function finalactivitykota($idkategori, $kota, $startdate = null, $enddate = null)
    {
        $this->startdate = $startdate;
        $this->enddate = $enddate;

        $this->kota_ = $kota;
        return $this->hasMany(Proposal::class, 'kategori_proposal', 'id')
                    ->where('status_proposal', 4)
                    ->where('kategori_proposal', $idkategori)
                    ->whereHas('dealer', function ($query) {
                        return $query->whereRaw('LOWER(kota_dealer) LIKE ? ', '%'.strtolower($this->kota_).'%');
                    })
                    ->whereBetween('periode_start_proposal', [$this->startdate, $this->enddate])
                    ->whereBetween('periode_end_proposal', [$this->startdate, $this->enddate]);
    }

    public function finalactivitydealer($idkategori, $dealer, $startdate = null, $enddate = null)
    {
        $this->startdate = $startdate;
        $this->enddate = $enddate;

        return $this->hasMany(Proposal::class, 'kategori_proposal', 'id')
                    ->where('status_proposal', 4)
                    ->where('kategori_proposal', $idkategori)
                    ->where('dealer_proposal', $dealer)
                    ->whereBetween('periode_start_proposal', [$this->startdate, $this->enddate])
                    ->whereBetween('periode_end_proposal', [$this->startdate, $this->enddate]);
                ;
    }

    public function akanberjalan($idkategori, $areakota, $iddealer, $startdate = null, $enddate = null)
    {
        $this->startdate = $startdate;
        $this->enddate = $enddate;

        $this->areakota = $areakota;
        $this->iddealer = $iddealer;
        return $this->hasMany(Proposal::class, 'kategori_proposal', 'id')
                        ->where('status_proposal', 4)
                        ->where('kategori_proposal', $idkategori)
                        ->where('periode_start_proposal', '>=', Carbon::now()->addDays(1)->format('Y-m-d'))
                        ->when($this->areakota, function ($query) {
                                if($this->areakota != 'SEMUA') {
                                    return $query->whereHas('dealer', function ($query) {
                                        return $query->whereRaw('LOWER(kota_dealer) LIKE ? ', '%'.strtolower($this->areakota).'%');
                                    });
                                }
                            })
                        ->when($this->iddealer, function ($query) {
                            if($this->iddealer != 'SEMUA') {
                                return $query->whereHas('dealer', function ($query) {
                                    return $query->where('id', $this->iddealer);
                                });
                            }
                        })
                        // ->where('periode_start_proposal', '<=', $this->enddate)
                        // ->where('periode_end_proposal', '>=', $this->startdate);
                        // ->where($this->startdate,'<=','periode_end_proposal')
                        // ->where($this->enddate,'>=','periode_start_proposal');
                        ->whereBetween('periode_start_proposal', [$this->startdate, $this->enddate])
                        ->whereBetween('periode_end_proposal', [$this->startdate, $this->enddate])
                        ;
    }

    public function sedangberjalan($idkategori, $areakota, $iddealer, $startdate = null, $enddate = null)
    {
        $this->startdate = $startdate;
        $this->enddate = $enddate;

        $this->areakota = $areakota;
        $this->iddealer = $iddealer;
        return $this->hasMany(Proposal::class, 'kategori_proposal', 'id')
                        ->where('status_proposal', 4)
                        ->where('kategori_proposal', $idkategori)
                        ->where('periode_start_proposal', '<', Carbon::now()->addDays(1)->format('Y-m-d'))
                        ->where('periode_end_proposal', '>', Carbon::now()->addDays(1)->format('Y-m-d'))
                        ->when($this->areakota, function ($query) {
                                if($this->areakota != 'SEMUA') {
                                    return $query->whereHas('dealer', function ($query) {
                                        return $query->whereRaw('LOWER(kota_dealer) LIKE ? ', '%'.strtolower($this->areakota).'%');
                                    });
                                }
                            })
                        ->when($this->iddealer, function ($query) {
                                if($this->iddealer != 'SEMUA') {
                                    return $query->whereHas('dealer', function ($query) {
                                        return $query->where('id', $this->iddealer);
                                    });
                                }
                        })
                        ->whereBetween('periode_start_proposal', [$this->startdate, $this->enddate])
                        ->whereBetween('periode_end_proposal', [$this->startdate, $this->enddate])
                        ;
    }

    public function selesai($idkategori, $areakota, $iddealer, $startdate = null, $enddate = null)
    {
        $this->startdate = $startdate;
        $this->enddate = $enddate;

        $this->areakota = $areakota;
        $this->iddealer = $iddealer;
        return $this->hasMany(Proposal::class, 'kategori_proposal', 'id')
                        ->where('status_proposal', 4)
                        ->where('kategori_proposal', $idkategori)
                        ->where('periode_end_proposal', '<', Carbon::now()->addDays(1)->format('Y-m-d'))
                        ->when($this->areakota, function ($query) {
                                if($this->areakota != 'SEMUA') {
                                    return $query->whereHas('dealer', function ($query) {
                                        return $query->whereRaw('LOWER(kota_dealer) LIKE ? ', '%'.strtolower($this->areakota).'%');
                                    });
                                }
                            })
                        ->when($this->iddealer, function ($query) {
                                if($this->iddealer != 'SEMUA') {
                                    return $query->whereHas('dealer', function ($query) {
                                        return $query->where('id', $this->iddealer);
                                    });
                                }
                            })
                        ->whereBetween('periode_start_proposal', [$this->startdate, $this->enddate])
                        ->whereBetween('periode_end_proposal', [$this->startdate, $this->enddate])
                        ;
    }

    //DEALER
    public function dealerfinalactivity($idkategori, $areakota, $iddealer, $tanggal = null, $waktu = null, $tahun = null)
    {
        $this->tahun_a = $tahun;
        $this->tahun_b = substr($waktu, 0, 4);
        $this->bulan_b = substr($waktu, 5, 7);
        $this->tanggal_a = $tanggal;

        $this->areakota = $areakota;
        $this->iddealer = $iddealer;
        return $this->hasMany(Proposal::class, 'kategori_proposal', 'id')
                            ->where('dealer_proposal', Auth::guard('cabang')->user()->dealer)
                            ->where('status_proposal', 4)
                            ->where('kategori_proposal', $idkategori)
                            ->when($this->areakota, function ($query) {
                                if($this->areakota != 'SEMUA') {
                                    return $query->whereHas('dealer', function ($query) {
                                        return $query->whereRaw('LOWER(kota_dealer) LIKE ? ', '%'.strtolower($this->areakota).'%');
                                    });
                                }
                            })
                            ->when($this->iddealer, function ($query) {
                                if($this->iddealer != 'SEMUA') {
                                    return $query->whereHas('dealer', function ($query) {
                                        return $query->where('id', $this->iddealer);
                                    });
                                }
                            })
                            ->when($this->tahun_a, function ($query_a_t) {
                                return $query_a_t->whereYear('submit_date', $this->tahun_a);
                            })
                            ->when($waktu, function ($query_a_w) {
                                return $query_a_w->whereMonth('submit_date', $this->bulan_b)->whereYear('submit_date', $this->tahun_b);
                            })
                            ->when($this->tanggal_a, function ($query_a_g) {
                                return $query_a_g->whereDate('submit_date', $this->tanggal_a);
                            });
    }

    public function dealerfinalactivity_data($idkategori)
    {
        return $this->hasMany(Proposal::class, 'kategori_proposal', 'id')->where('dealer_proposal', Auth::guard('cabang')->user()->dealer)->where('status_proposal', 4)->where('kategori_proposal', $idkategori);
    }

    public function dealerfinalactivitykota($idkategori, $kota, $tanggal = null, $waktu = null, $tahun = null)
    {
        $this->tahun_a = $tahun;
        $this->tahun_b = substr($waktu, 0, 4);
        $this->bulan_b = substr($waktu, 5, 7);
        $this->tanggal_a = $tanggal;

        $this->kota_ = $kota;
        return $this->hasMany(Proposal::class, 'kategori_proposal', 'id')
                        // ->where('dealer_proposal', Auth::guard('cabang')->user()->dealer)
                    ->where('status_proposal', 4)
                    ->where('kategori_proposal', $idkategori)
                    ->whereHas('dealer', function ($query) {
                        return $query->whereRaw('LOWER(kota_dealer) LIKE ? ', '%'.strtolower($this->kota_).'%');
                    })
                    ->when($this->tahun_a, function ($query_a_t) {
                        return $query_a_t->whereYear('submit_date', $this->tahun_a);
                    })
                    ->when($waktu, function ($query_a_w) {
                        return $query_a_w->whereMonth('submit_date', $this->bulan_b)->whereYear('submit_date', $this->tahun_b);
                    })
                    ->when($this->tanggal_a, function ($query_a_g) {
                        return $query_a_g->whereDate('submit_date', $this->tanggal_a);
                    })
                    ;
    }

    public function dealerfinalactivitydealer($idkategori, $dealer, $tanggal = null, $waktu = null, $tahun = null)
    {
        $this->tahun_a = $tahun;
        $this->tahun_b = substr($waktu, 0, 4);
        $this->bulan_b = substr($waktu, 5, 7);
        $this->tanggal_a = $tanggal;

        return $this->hasMany(Proposal::class, 'kategori_proposal', 'id')
                    // ->where('dealer_proposal', Auth::guard('cabang')->user()->dealer)
                    ->where('status_proposal', 4)
                    ->where('kategori_proposal', $idkategori)
                    ->where('dealer_proposal', $dealer)
                    ->when($this->tahun_a, function ($query_a_t) {
                        return $query_a_t->whereYear('submit_date', $this->tahun_a);
                    })
                    ->when($waktu, function ($query_a_w) {
                        return $query_a_w->whereMonth('submit_date', $this->bulan_b)->whereYear('submit_date', $this->tahun_b);
                    })
                    ->when($this->tanggal_a, function ($query_a_g) {
                        return $query_a_g->whereDate('submit_date', $this->tanggal_a);
                    })
                ;
    }

    public function dealerakanberjalan($idkategori, $areakota, $iddealer, $tanggal = null, $waktu = null, $tahun = null)
    {
        $this->tahun_a = $tahun;
        $this->tahun_b = substr($waktu, 0, 4);
        $this->bulan_b = substr($waktu, 5, 7);
        $this->tanggal_a = $tanggal;

        $this->areakota = $areakota;
        $this->iddealer = $iddealer;
        return $this->hasMany(Proposal::class, 'kategori_proposal', 'id')
                        ->where('dealer_proposal', Auth::guard('cabang')->user()->dealer)
                        ->where('status_proposal', 4)
                        ->where('kategori_proposal', $idkategori)
                        ->where('periode_start_proposal', '>', Carbon::now()->addDays(1)->format('Y-m-d'))
                        ->when($this->areakota, function ($query) {
                                if($this->areakota != 'SEMUA') {
                                    return $query->whereHas('dealer', function ($query) {
                                        return $query->whereRaw('LOWER(kota_dealer) LIKE ? ', '%'.strtolower($this->areakota).'%');
                                    });
                                }
                            })
                        ->when($this->iddealer, function ($query) {
                            if($this->iddealer != 'SEMUA') {
                                return $query->whereHas('dealer', function ($query) {
                                    return $query->where('id', $this->iddealer);
                                });
                            }
                        })
                        ->when($this->tahun_a, function ($query_a_t) {
                            return $query_a_t->whereYear('submit_date', $this->tahun_a);
                        })
                        ->when($waktu, function ($query_a_w) {
                            return $query_a_w->whereMonth('submit_date', $this->bulan_b)->whereYear('submit_date', $this->tahun_b);
                        })
                        ->when($this->tanggal_a, function ($query_a_g) {
                            return $query_a_g->whereDate('submit_date', $this->tanggal_a);
                        })
                        ;
    }

    public function dealersedangberjalan($idkategori, $areakota, $iddealer, $tanggal = null, $waktu = null, $tahun = null)
    {
        $this->tahun_a = $tahun;
        $this->tahun_b = substr($waktu, 0, 4);
        $this->bulan_b = substr($waktu, 5, 7);
        $this->tanggal_a = $tanggal;

        $this->areakota = $areakota;
        $this->iddealer = $iddealer;
        return $this->hasMany(Proposal::class, 'kategori_proposal', 'id')
                        ->where('dealer_proposal', Auth::guard('cabang')->user()->dealer)
                        ->where('status_proposal', 4)
                        ->where('kategori_proposal', $idkategori)
                        ->where('periode_start_proposal', '<', Carbon::now()->addDays(1)->format('Y-m-d'))
                        ->where('periode_end_proposal', '>', Carbon::now()->addDays(1)->format('Y-m-d'))
                        ->when($this->areakota, function ($query) {
                                if($this->areakota != 'SEMUA') {
                                    return $query->whereHas('dealer', function ($query) {
                                        return $query->whereRaw('LOWER(kota_dealer) LIKE ? ', '%'.strtolower($this->areakota).'%');
                                    });
                                }
                            })
                        ->when($this->iddealer, function ($query) {
                                if($this->iddealer != 'SEMUA') {
                                    return $query->whereHas('dealer', function ($query) {
                                        return $query->where('id', $this->iddealer);
                                    });
                                }
                        })
                        ->when($this->tahun_a, function ($query_a_t) {
                                return $query_a_t->whereYear('submit_date', $this->tahun_a);
                        })
                        ->when($waktu, function ($query_a_w) {
                            return $query_a_w->whereMonth('submit_date', $this->bulan_b)->whereYear('submit_date', $this->tahun_b);
                        })
                        ->when($this->tanggal_a, function ($query_a_g) {
                            return $query_a_g->whereDate('submit_date', $this->tanggal_a);
                        })
                        ;
    }

    public function dealerselesai($idkategori, $areakota, $iddealer, $tanggal = null, $waktu = null, $tahun = null)
    {
        $this->tahun_a = $tahun;
        $this->tahun_b = substr($waktu, 0, 4);
        $this->bulan_b = substr($waktu, 5, 7);
        $this->tanggal_a = $tanggal;

        $this->areakota = $areakota;
        $this->iddealer = $iddealer;
        return $this->hasMany(Proposal::class, 'kategori_proposal', 'id')
                        ->where('dealer_proposal', Auth::guard('cabang')->user()->dealer)
                        ->where('status_proposal', 4)
                        ->where('kategori_proposal', $idkategori)
                        ->where('periode_end_proposal', '<', Carbon::now()->addDays(1)->format('Y-m-d'))
                        ->when($this->areakota, function ($query) {
                                if($this->areakota != 'SEMUA') {
                                    return $query->whereHas('dealer', function ($query) {
                                        return $query->whereRaw('LOWER(kota_dealer) LIKE ? ', '%'.strtolower($this->areakota).'%');
                                    });
                                }
                            })
                        ->when($this->iddealer, function ($query) {
                                if($this->iddealer != 'SEMUA') {
                                    return $query->whereHas('dealer', function ($query) {
                                        return $query->where('id', $this->iddealer);
                                    });
                                }
                            })
                        ->when($this->tahun_a, function ($query_a_t) {
                                return $query_a_t->whereYear('submit_date', $this->tahun_a);
                        })
                        ->when($waktu, function ($query_a_w) {
                            return $query_a_w->whereMonth('submit_date', $this->bulan_b)->whereYear('submit_date', $this->tahun_b);
                        })
                        ->when($this->tanggal_a, function ($query_a_g) {
                            return $query_a_g->whereDate('submit_date', $this->tanggal_a);
                        })
                        ;
    }



}
