<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QurbanAnimal extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'nama_hewan',
        'batas_peserta',
        'perkiraan_harga',
    ];

    public function qurbanGroups()
    {
        return $this->hasMany(QurbanGroup::class);
    }
}
