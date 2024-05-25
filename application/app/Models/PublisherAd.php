<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublisherAd extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function advertise()
    {
        return $this->belongsTo(CreateAd::class,'create_ad_id');
    }
    public function adTypeDetail()
    {
        return $this->belongsTo(AdType::class,'ad_type_id');
    }
}
