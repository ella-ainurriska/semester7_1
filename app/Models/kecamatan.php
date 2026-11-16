<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'latitude', 'longitude', 'geojson'];

    protected $casts = [
        'geojson' => 'array',
    ];

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }
}