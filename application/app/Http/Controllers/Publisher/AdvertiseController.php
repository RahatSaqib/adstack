<?php

namespace App\Http\Controllers\Publisher;

use App\Models\AdType;
use App\Models\PublisherAd;
use App\Http\Controllers\Controller;

class AdvertiseController extends Controller
{
    public function advertises($type)
    {
        $ads = [];
        if ($type == 'adult') {
            $ads = AdType::where('status', 1)->where('is_adult', 1)->latest()->paginate(getPaginate());
        }
        else if($type == 'link'){
            $ads = AdType::where('status', 1)->where('type', 'third-party-link')->latest()->paginate(getPaginate());
        }
        else{
            $ads = AdType::where('status', 1)->where('is_adult', 0)->latest()->paginate(getPaginate());
        }
        $pageTitle = 'Advertise Types';
        return view($this->activeTemplate . 'publisher.advertises.advertises', compact('ads', 'pageTitle' , 'type'));
    }

    public function allAdvertises()
    {

        $ads = AdType::where('status', 1)->latest()->paginate(getPaginate());
        $pageTitle = 'Advertise Types';
        return view($this->activeTemplate . 'publisher.advertises.advertises', compact('ads', 'pageTitle'));
    }

    public function publishedAd()
    {
        $pageTitle = 'Published Ads';
        $publisherAds = PublisherAd::where('publisher_id', auth()->guard('publisher')->user()->id)
            ->with('advertise')->with('adTypeDetail')->paginate(getPaginate());
        return view($this->activeTemplate . 'publisher.advertises.published_ad', compact('pageTitle', 'publisherAds'));
    }
}
