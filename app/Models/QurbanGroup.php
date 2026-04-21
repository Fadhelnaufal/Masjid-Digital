<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QurbanGroup extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'qurban_animal_id',
        'nama_kelompok',
        'tahun_masehi',
        'tahun_hijriah',
        'status_distribusi',
    ];

    public function qurbanAnimal()
    {
        return $this->belongsTo(QurbanAnimal::class, 'qurban_animal_id');
    }

    public function shohibulQurbans()
    {
        return $this->hasMany(ShohibulQurban::class);
    }
}
