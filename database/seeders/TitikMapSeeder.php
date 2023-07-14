<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TitikMap;

class TitikMapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TitikMap::create([
            'id' => 1,
            'kota' => 'BALIKPAPAN',
            'latitude' => '-1.216637170564',
            'longtitude' => '116.86994888819',
            'zoom' => '13',
        ]);
        TitikMap::create([
            'id' => 2,
            'kota' => 'SAMARINDA',
            'latitude' => '-0.4953834998494',
            'longtitude' => '117.14123178273',
            'zoom' => '13',
        ]);
        TitikMap::create([
            'id' => 3,
            'kota' => 'PENAJAM PASER UTARA',
            'latitude' => '-1.291932803505',
            'longtitude' => '116.57060292083',
            'zoom' => '12',
        ]);
        TitikMap::create([
            'id' => 4,
            'kota' => 'PASER',
            'latitude' => '-1.927740822120',
            'longtitude' => '116.16899530403',
            'zoom' => '11',
        ]);
        TitikMap::create([
            'id' => 5,
            'kota' => 'TARAKAN',
            'latitude' => '3.3581355590969',
            'longtitude' => '117.60592508129',
            'zoom' => '13',
        ]);
        TitikMap::create([
            'id' => 6,
            'kota' => 'BERAU',
            'latitude' => '2.1503136001922',
            'longtitude' => '117.49150108080',
            'zoom' => '12',
        ]);
        TitikMap::create([
            'id' => 7,
            'kota' => 'NUNUKAN',
            'latitude' => '4.0665258191477',
            'longtitude' => '117.66698265681',
            'zoom' => '12',
        ]);
        TitikMap::create([
            'id' => 8,
            'kota' => 'BULUNGAN',
            'latitude' => '2.7744866986617',
            'longtitude' => '117.35327195143',
            'zoom' => '10',
        ]);
    }
}
