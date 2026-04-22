<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TakjilSchedule extends Model
{
    protected $fillable = ['tahun_masehi', 'hari_ke', 'tanggal_masehi', 'nama_donatur', 'jumlah_porsi', 'tipe', 'is_confirmed'];
}