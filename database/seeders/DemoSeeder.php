<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kecamatan;
use App\Models\Kriteria;
use App\Models\Nilai;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Kriteria Kemiskinan
        $k1 = Kriteria::create(['kode' => 'C1', 'nama' => 'Jumlah KK Prasejahtera', 'bobot' => 4, 'tipe' => 'benefit']);
        $k2 = Kriteria::create(['kode' => 'C2', 'nama' => 'Rumah Tidak Layak Huni (RTLH)', 'bobot' => 3, 'tipe' => 'benefit']);
        $k3 = Kriteria::create(['kode' => 'C3', 'nama' => 'Persentase Putus Sekolah', 'bobot' => 3, 'tipe' => 'benefit']);
        $k4 = Kriteria::create(['kode' => 'C4', 'nama' => 'Keluarga Tanpa Akses Air Bersih', 'bobot' => 2, 'tipe' => 'benefit']);

        // 2. Sample Kecamatan Pamekasan
        $dataKec = [
            ['nama' => 'Proppo', 'lat' => -7.1350, 'lng' => 113.4300, 'nilai' => [3500, 420, 18.5, 310]],
            ['nama' => 'Kadur', 'lat' => -7.0850, 'lng' => 113.5600, 'nilai' => [2800, 310, 14.2, 220]],
            ['nama' => 'Batumarmar', 'lat' => -6.9200, 'lng' => 113.5100, 'nilai' => [4100, 530, 22.0, 410]],
            ['nama' => 'Pademawu', 'lat' => -7.1800, 'lng' => 113.5150, 'nilai' => [1900, 190, 8.5, 120]],
            ['nama' => 'Tlanakan', 'lat' => -7.2100, 'lng' => 113.4600, 'nilai' => [2300, 240, 11.0, 180]],
        ];

        $kriterias = [$k1, $k2, $k3, $k4];

        foreach ($dataKec as $d) {
            $kec = Kecamatan::create([
                'nama'      => $d['nama'],
                'latitude'  => $d['lat'],
                'longitude' => $d['lng'],
            ]);

            foreach ($d['nilai'] as $idx => $val) {
                Nilai::create([
                    'kecamatan_id' => $kec->id,
                    'kriteria_id'  => $kriterias[$idx]->id,
                    'nilai'        => $val,
                ]);
            }
        }
    }
}