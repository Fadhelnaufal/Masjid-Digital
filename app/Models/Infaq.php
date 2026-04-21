<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Infaq extends Model
{
    protected $fillable = [
        'tanggal',
        'nominal',
        'keterangan',
    ];
}
