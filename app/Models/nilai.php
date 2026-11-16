<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $fillable = ['kecamatan_id', 'kriteria_id', 'nilai'];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}