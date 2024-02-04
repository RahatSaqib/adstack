<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    public function user()
    {
    	return $this->belongsTo(Advertiser::class);
    }
}
