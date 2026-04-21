<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShohibulQurban extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'qurban_group_id',
        'jamaah_id',
        'nama_peserta',
        'nominal_iuran',
        'status_pembayaran',
    ];

    public function qurbanGroup()
    {
        return $this->belongsTo(QurbanGroup::class);
    }

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class);
    }
}
