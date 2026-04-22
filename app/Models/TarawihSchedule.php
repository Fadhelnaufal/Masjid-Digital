<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarawihSchedule extends Model
{
    protected $fillable = [
        'tahun_masehi', 
        'hari_ke', 
        'tanggal_masehi', 
        'imam_id',           // Ini Imam Utama
        'imam_cadangan_id', 
        'imam_subuh_id', 
        'bilal', 
        'penceramah', 
        'tema_ceramah'
    ];

    // Relasi ke Imam Utama
    public function imam()
    {
        return $this->belongsTo(Imam::class, 'imam_id');
    }

    // Relasi ke Imam Cadangan
    public function imamCadangan()
    {
        return $this->belongsTo(Imam::class, 'imam_cadangan_id');
    }

    // Relasi ke Imam Subuh
    public function imamSubuh()
    {
        return $this->belongsTo(Imam::class, 'imam_subuh_id');
    }
}