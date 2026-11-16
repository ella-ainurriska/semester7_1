<?php

namespace App\Services;

use App\Models\Kecamatan;
use App\Models\Kriteria;

class TopsisService
{
    public function hitung(): array
    {
        $kriterias  = Kriteria::orderBy('id')->get();
        $kecamatans = Kecamatan::with('nilais')->orderBy('id')->get();

        if ($kriterias->isEmpty() || $kecamatans->isEmpty()) {
            return [];
        }

        // 1. Matriks Keputusan (X)
        $x = [];
        foreach ($kecamatans as $kec) {
            $nilaiMap = $kec->nilais->pluck('nilai', 'kriteria_id');
            foreach ($kriterias as $k) {
                $x[$kec->id][$k->id] = (float) ($nilaiMap[$k->id] ?? 0);
            }
        }

        // 2 & 3. Normalisasi Matriks (R) dan Matriks Berbobot (Y)
        $totalBobot = $kriterias->sum('bobot') ?: 1;
        $y = [];
        foreach ($kriterias as $k) {
            $akar = sqrt(array_sum(array_map(fn ($row) => $row[$k->id] ** 2, $x))) ?: 1;
            $w = $k->bobot / $totalBobot;
            foreach ($x as $kecId => $row) {
                $y[$kecId][$k->id] = ($row[$k->id] / $akar) * $w;
            }
        }

        // 4. Solusi Ideal Positif (A+) & Negatif (A-)
        $aPlus = [];
        $aMinus = [];
        foreach ($kriterias as $k) {
            $kolom = array_column($y, $k->id);
            $aPlus[$k->id]  = ($k->tipe === 'benefit') ? max($kolom) : min($kolom);
            $aMinus[$k->id] = ($k->tipe === 'benefit') ? min($kolom) : max($kolom);
        }

        // 5 & 6. Jarak Euclidean (D+ & D-) dan Nilai Preferensi (V)
        $hasil = [];
        foreach ($kecamatans as $kec) {
            $dPlus = 0;
            $dMinus = 0;
            foreach ($kriterias as $k) {
                $dPlus  += ($y[$kec->id][$k->id] - $aPlus[$k->id]) ** 2;
                $dMinus += ($y[$kec->id][$k->id] - $aMinus[$k->id]) ** 2;
            }
            $dPlus  = sqrt($dPlus);
            $dMinus = sqrt($dMinus);
            $v = ($dPlus + $dMinus) == 0 ? 0 : $dMinus / ($dPlus + $dMinus);

            $hasil[] = [
                'kecamatan_id' => $kec->id,
                'nama'         => $kec->nama,
                'latitude'     => (float) $kec->latitude,
                'longitude'    => (float) $kec->longitude,
                'geojson'      => $kec->geojson,
                'd_plus'       => round($dPlus, 4),
                'd_minus'      => round($dMinus, 4),
                'preferensi'   => round($v, 4),
            ];
        }

        // 7. Perankingan (Preferensi terbesar = Prioritas 1)
        usort($hasil, fn ($a, $b) => $b['preferensi'] <=> $a['preferensi']);

        foreach ($hasil as $i => &$item) {
            $item['peringkat'] = $i + 1;
            $item['kategori']  = $item['preferensi'] >= 0.66 ? 'Tinggi'
                : ($item['preferensi'] >= 0.33 ? 'Sedang' : 'Rendah');
        }

        return $hasil;
    }
}