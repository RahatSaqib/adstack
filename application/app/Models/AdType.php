<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdType extends Model
{
    use HasFactory;

    public function scopeActive()
    {
        return $this->where('status', 1);
    }
    public function tpCost()
    {
        return $this->belongsTo(ThirdPartyCostData::class,'tp_cost');
    }
}
