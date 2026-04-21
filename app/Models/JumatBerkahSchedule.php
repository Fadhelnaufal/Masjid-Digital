<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JumatBerkahSchedule extends Model
{
    
    protected $fillable = [
      'tanggal',
        'target_porsi',
        'status',  
    ];

    public function donations()
    {
        return $this->hasMany(JumatBerkahDonation::class, 'schedule_id');
    }
}
