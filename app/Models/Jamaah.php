<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jamaah extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'nik',
        'nama_lengkap',
        'alamat',
        'no_whatsapp',
    ];

    public function shohibulQurbans()
    {
        return $this->hasMany(ShohibulQurban::class);
    }
}
