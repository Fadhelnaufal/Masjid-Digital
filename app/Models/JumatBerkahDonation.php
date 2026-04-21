<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JumatBerkahDonation extends Model
{


    protected $fillable = [
        'schedule_id',
        'nama_donatur',
        'jenis_donasi',
        'jumlah_donasi',
        'jumlah_porsi',
        'status_distribusi',
    ];

    public function schedule()
    {
        return $this->belongsTo(JumatBerkahSchedule::class, 'schedule_id');
    }
}
