<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarningLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'publisher_id',
        'ad_id',
        'ad_type',
        'ad_type_id',
        'date',
        'amount'
    ];
    public function adType()
    {
        return $this->belongsTo(AdType::class,'ad_type_id');
    }
    public function ad()
    {
        return $this->belongsTo(CreateAd::class,'ad_id');
    }
    public function publisher()
    {
        return $this->belongsTo(Publisher::class,'publisher_id');
    }
}
