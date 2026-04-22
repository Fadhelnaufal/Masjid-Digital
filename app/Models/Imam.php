<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imam extends Model
{
    protected $fillable = ['nama', 'no_hp', 'alamat'];

    public function tarawihSchedules()
    {
        return $this->hasMany(TarawihSchedule::class);
    }
}
