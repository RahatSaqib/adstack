<?php

namespace App\Http\Controllers;

use App\Models\IpLog;
use App\Models\AdType;
use App\Models\IpChart;
use App\Models\Analytic;
use App\Models\CreateAd;
use App\Models\Purchase;
use App\Models\Publisher;
use App\Models\Advertiser;
use App\Models\EarningLog;
use App\Models\PublisherAd;
use Illuminate\Support\Carbon;
use App\Models\DomainVerifcation;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Request;

class VisitorController extends Controller
{

    protected function defaultAd($slug, $width, $height, $title)
    {
        $logo = route('placeholder.image', $slug);
        return "<a href='" . url('/') . "' target='_blank'><img src='" . $logo . "' width='" . $width . "' height='" . $height . "'/></a><strong style='background-color:#e6e6e6;position:absolute;right:0;top:0;font-size: 10px;color: #666666; padding:4px; margin-right:15px;'>Ads by " . $title . "</strong><span onclick='hideAdverTiseMent(this)' style='position:absolute;right:0;top:0;width:15px;height:20px;background-color:#f00;font-size: 15px;color: #fff;border-radius: 1px;cursor: pointer;'>x</span>";
    }

    public function randomAd($redirectUrl, $adImage, $width, $height, $sitename)
    {
        return "<a href='" . $redirectUrl . "' target='_blank'><img src='" . $adImage . "' width='" . $width . "' height='" . $height . "'/></a><strong style='background-color:#e6e6e6;position:absolute;right:0;top:0;font-size: 10px;color: #666666; padding:4px; margin-right:15px;'>Ads by " . $sitename . "</strong><span onclick='hideAdverTiseMent(this)' style='position:absolute;right:0;top:0;width:15px;height:20px;background-color:#f00;font-size: 15px;color: #fff;border-radius: 1px;cursor: pointer;'>x</span>";
    }

    public function getIp()
    {
        if (Request::server('HTTP_CLIENT_IP')) {
            return Request::server('HTTP_CLIENT_IP');
        } elseif (Request::server('HTTP_X_FORWARDED_FOR')) {
            return Request::server('HTTP_X_FORWARDED_FOR');
        } elseif (Request::server('REMOTE_ADDR')) {
            return Request::server('REMOTE_ADDR');
        } else {
            return Request::ip() ? Request::ip() : '';
        }
    }
    public static function validate_ipv4_regex($ip)
    {
        $pattern = "/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/";
        return preg_match($pattern, $ip) === 1;
    }

    public static function validate_ipv6_regex($ip)
    {
        $pattern = "/^((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){1,7}:)|(([0-9A-Fa-f]{1,4}:){1,6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,5}(:[0-9A-Fa-f]{1,4}){1,2})|(([0-9A-Fa-f]{1,4}:){1,4}(:[0-9A-Fa-f]{1,4}){1,3})|(([0-9A-Fa-f]{1,4}:){1,3}(:[0-9A-Fa-f]{1,4}){1,4})|(([0-9A-Fa-f]{1,4}:){1,2}(:[0-9A-Fa-f]{1,4}){1,5})|([0-9A-Fa-f]{1,4}:((:[0-9A-Fa-f]{1,4}){1,6}))|(:((:[0-9A-Fa-f]{1,4}){1,7}|:))|(::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9])?[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9])?[0-9])|([0-9A-Fa-f]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9])?[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9])?[0-9])))$/";
        return preg_match($pattern, $ip) === 1;
    }
    public function getAdvertise($pubId, $slug, $currentUrl)
    {
        header("Access-Control-Allow-Origin: *");
        $publisherId = Crypt::decryptString($pubId);
        $adType = AdType::whereSlug($slug)->where('status', 1)->first();
        $setting = gs();

        $existingIp = IpChart::firstOrNew(['ip' => $this->getIp()]);
        if ($existingIp->blocked == 1) {
            return $this->defaultAd($slug, $adType->width, $adType->height, $setting->site_name);
        }
        $existingIp->save();

        $domain = DomainVerifcation::where('name', $currentUrl)
            ->where('publisher_id', $publisherId)
            ->where('status', 1)->first();

        if (!$domain) {
            info("Domain not found or unverified");
            return $this->defaultAd($slug, $adType->width, $adType->height, $setting->site_name);
        }


        // $query = getIpInfo();
        $query = json_decode(file_get_contents('http://api.ipstack.com/' . $this->getIp() . '?access_key=' . $setting->location_api));

        if (@$query->error) {
            info("IP tracking  error", [
                'error' => @$query->error
            ]);
            return $this->defaultAd($slug, $adType->width, $adType->height, $setting->sitename);
        }

        if ($adType) {

            $queryAd = CreateAd::where('ad_type_id', $adType->id)->where('status', 1);


            if ($setting->check_country && $query->country_name) {
                $queryAd->whereJsonContains('country', $query->country_name);
            }

            if ($setting->check_domain_keyword && $domain) {
                $domainKeywords = json_decode($domain->keywords);
                if ($domainKeywords) {
                    $queryAd->where(function ($q) use ($domainKeywords) {
                        foreach ($domainKeywords as $keyword) {
                            $q->orWhere('keywords', 'LIKE', "%$keyword%");
                        }
                    });
                }
            }

            $ads = $queryAd->inRandomOrder()->first();

            if (empty($ads)) {
                return $this->defaultAd($slug, $adType->width, $adType->height, $setting->sitename);
            }

            $existIpLog = $existingIp->iplogs
                ->where('ad_id', $ads->id)
                ->where('time', '>=', Carbon::now()->subMinutes(1))
                ->first();

            if ($ads) {
                $publisher = Publisher::findOrFail($publisherId);
                $publisherAd = PublisherAd::firstOrNew([
                    'create_ad_id' => $ads->id,
                    'publisher_id' => $publisher->id,
                    'date' => Carbon::now()->toDateString()
                ]);
                $publisherAd->advertiser_id = $ads->advertiser_id;
                $publisherAd->imp_count += 1;
                $publisherAd->save();

                if ($ads->ad_type == 'impression') {

                    $advertiser = Advertiser::findOrFail($ads->advertiser_id);

                    if (!$existIpLog) {
                        $ipLog = new IpLog();
                        $ipLog->ip_id = $existingIp->id;
                        $ipLog->country = @$query->country_name;
                        $ipLog->ad_id = $ads->id;
                        $ipLog->ad_type = $ads->ad_type;
                        $ipLog->time = Carbon::now()->toTimeString();
                        $ipLog->save();

                        $ipcart = IpLog::with('ip')->where('created_at', '>=', Carbon::now()->subHours(24))->get();
                        $uniqueIps = $ipcart->pluck('ip.ip')->count();
                        if ($uniqueIps > $setting->same_ip_limit) {
                        } else {
                            if (@$advertiser->impression > 0) {
                                $advertiser->impression -= 1;
                                $advertiser->update();
                            } else {
                                $ads->status = 0;
                                $ads->update();
                                Purchase::where('advertiser_id', $ads->advertiser_id)
                                    ->where('type', 'impression')->delete();
                            }

                            if ($publisher) {
                                $publisher->balance += $setting->cpm_pub;
                                $publisher->update();

                                $earningLog = EarningLog::firstOrNew([
                                    'publisher_id' => $publisher->id,
                                    'ad_id' => $ads->id,
                                ]);

                                $earningLog->amount += $setting->cpm_pub;;
                                $earningLog->ad_type = $ads->ad_type;
                                $earningLog->save();
                            }
                        }
                    }
                }

                $redirectUrl = route('adClicked', [
                    encrypt($publisherId),
                    $ads->track_id,
                    $existingIp
                ]);

                $adImage = asset('assets/images/frontend/adImage') . '/' . $ads->image;
                $ads->impression += 1;
                $ads->update();

                $analytic = Analytic::firstOrNew([
                    'country' => @$query->country_name,
                    'advertiser_id' => $ads->advertiser_id,
                    'ad_id' => $ads->id
                ]);

                $analytic->ad_title = $ads->title;
                $analytic->imp_count += 1;
                $analytic->save();
            } else {
                info("Ad not found. ad type: " . $adType->slug);
                return $this->defaultAd($slug, $adType->width, $adType->height, $setting->site_name);
            }
        } else {
            info("Ad type not found. ad type: request ad type slug: " . $slug);
            return $this->defaultAd($slug, $adType->width, $adType->height, $setting->site_name);
        }

        return $this->randomAd($redirectUrl, $adImage, $adType->width, $adType->height, $setting->site_name);
    }

    public function getThirdPartyAdvertise(HttpRequest $request)
    {
        $pubId = $request->input('publisherId');
        $adTypeId = $request->input('adTypeId');
        $currentUrl = $request->input('currentUrl');
        $IPHash = $request->input('IPHash');
        $IP = $request->input('IP');
        $calculatedHash = hash('sha256', $IP);
        if ($IPHash == $calculatedHash) {
            $publisherId = Crypt::decryptString($pubId);
            $adType = AdType::with('tpCost')->where('id', $adTypeId)->where('status', 1)->first();
            $this->processImpression($publisherId, $adType, $currentUrl, false, $IP);
        } else {
            return response()->json(['message' => 'Hashes do not match'], 400);
        }
    }

    public function processImpression($publisherId, $adType, $currentUrl, $isLink, $IP)
    {

        $setting = gs();
        if (!$this::validate_ipv6_regex($IP) && !$this::validate_ipv4_regex($IP)) {
            return;
        }

        $existingIp = IpChart::firstOrNew(['ip' => $IP]);
        if ($existingIp->blocked == 1) {
            return;
        }
        $existingIp->save();

        if ($isLink == false) {
            $domain = DomainVerifcation::where('name', $currentUrl)
                ->where('publisher_id', $publisherId)
                ->where('status', 1)->first();
            if (!$domain) {
                info("Domain not found or unverified");
                return;
            }
        }

        // dd($domain);


        // $query = getIpInfo();
        // $query = json_decode(file_get_contents('http://api.ipstack.com/' . $this->getIp() . '?access_key=' . $setting->location_api));

        // if (@$query->error) {
        //     info("IP tracking  error", [
        //         'error' => @$query->error
        //     ]);
        //     return;
        // }

        if ($adType) {
            $existIpLog = $existingIp->iplogs
                ->where('ad_type_id', $adType->id)
                ->where('is_impression', 1)
                ->where('time', '>=', Carbon::now()->subMinutes(1))
                ->first();

            $publisher = Publisher::findOrFail($publisherId);
            $publisherAd = PublisherAd::firstOrNew([
                'ad_type_id' => $adType->id,
                'publisher_id' => $publisher->id,
                'date' => Carbon::now()->toDateString()
            ]);
            $publisherAd->imp_count += 1;
            $publisherAd->save();
            if ((!$existIpLog && $adType->is_impression == 1) || $isLink == true) {
                $ipLog = new IpLog();
                $ipLog->ip_id = $existingIp->id;
                $ipLog->country = "Unknown";
                $ipLog->ad_type_id = $adType->id;
                $ipLog->ad_type = $adType->type;
                $ipLog->is_impression = $adType->is_impression;
                $ipLog->time = Carbon::now()->toTimeString();
                $ipLog->save();
                $ipcart = IpLog::with('ip')->where('ad_type_id', $adType->id)->where('is_impression', 1)->where('created_at', '>=', Carbon::now()->subHours(24))->get();
                $uniqueIps = $ipcart->pluck('ip.ip')->count();
                if ($uniqueIps > $setting->same_ip_limit && $isLink == false) {
                } else {
                    if ($publisher) {

                        $publisher->balance += $adType->tpCost->cpm_pub / $setting->cost_unit;
                        $publisher->update();

                        $earningLog = EarningLog::where('publisher_id', $publisher->id)
                            ->where('ad_type', $adType->type)
                            ->where('ad_type_id', $adType->id)
                            ->where('created_at', '>=', Carbon::now()->subHours(24))
                            ->first();
                        if (!isset($earningLog)) {
                            $earningLog = EarningLog::create(
                                [
                                    'publisher_id' => $publisher->id,
                                    'ad_type_id' => $adType->id,
                                    'ad_type' => $adType->type,
                                    'amount' => $adType->tpCost->cpm_pub / $setting->cost_unit
                                ]
                            );
                        } else {
                            $earningLog->amount += $adType->tpCost->cpm_pub / $setting->cost_unit;
                            $earningLog->save();
                        }
                    }
                }
            }
        } else {
            info("Ad type not found. ad type: request ad type slug: ");
            return;
        }

        return;
    }

    public function thirdPartyLink($pubId, $adTypeId)
    {
        header("Access-Control-Allow-Origin: *");
        $adminBrowser = osBrowser();
        if ($adminBrowser['os_platform'] == 'Bot' || $adminBrowser['browser'] == 'Unknown Browser') {
            return;
        }
        $publisherId = Crypt::decryptString($pubId);
        $adType = AdType::with('tpCost')->where('id', $adTypeId)->where('status', 1)->first();

        if ($adType->third_party_script) {
            $this->processImpression($publisherId, $adType, '', true, $this->getIp());
            return redirect($adType->third_party_script);
        }
        return;
    }

    public function adClicked($publisherId, $trackId)
    {
        $ad = CreateAd::where('track_id', $trackId)->first();
        $setting = gs();

        // $query = getIpInfo();
        $query = json_decode(file_get_contents('http://api.ipstack.com/' . $this->getIp() . '?access_key=' . $setting->location_api));

        $existingIp = IpChart::where('ip', $this->getIp())->first();
        $publisher = Publisher::findOrFail(decrypt($publisherId));
        $advertiser = Advertiser::findOrFail($ad->advertiser_id);

        $existIpLog = $existingIp->iplogs
            ->where('ad_id', $ad->id)
            ->where('time', '>=', Carbon::now()->subMinutes(1))
            ->first();

        if ($ad) {
            $publisherAd = PublisherAd::firstOrNew([
                'create_ad_id' => $ad->id,
                'publisher_id' => $publisher->id,
                'date' => Carbon::now()->toDateString()
            ]);

            $publisherAd->advertiser_id = $ad->advertiser_id;
            $publisherAd->click_count += 1;
            $publisherAd->save();

            if ($ad->ad_type == 'click') {
                // $ifPurchaseAdvertiser = getSubscriptionVisitor($advertiser->id, 'click');
                $ifPurchaseAdvertiser = Advertiser::findOrFail($advertiser->id);
                if (!$existIpLog) {
                    $ipLog = new IpLog();
                    $ipLog->ip_id = $existingIp->id;
                    $ipLog->country = $query->country_name;
                    $ipLog->ad_id = $ad->id;
                    $ipLog->ad_type = $ad->ad_type;
                    $ipLog->time = Carbon::now()->toTimeString();
                    $ipLog->save();


                    $ipcart = IpLog::with('ip')->where('is_impression', 1)->where('created_at', '>=', Carbon::now()->subHours(24))->get();
                    $uniqueIps = $ipcart->pluck('ip.ip')->count();

                    if ($uniqueIps > $setting->same_ip_limit) {
                    } else {

                        if (@$ifPurchaseAdvertiser->click > 0) {
                            $ifPurchaseAdvertiser->click -= 1;
                            $ifPurchaseAdvertiser->update();
                        } else {
                            $ad->status = 0;
                            $ad->update();
                            Purchase::where('advertiser_id', $advertiser->id)
                                ->where('type', 'click')->delete();
                        }

                        if ($publisher) {
                            $publisher->balance += $setting->cpc_pub;
                            $publisher->update();

                            $earningLog = EarningLog::firstOrNew([
                                'publisher_id' => $publisher->id,
                                'ad_id' => $ad->id,
                            ]);

                            $earningLog->amount +=  $setting->cpc_pub;
                            $earningLog->ad_type = $ad->ad_type;
                            $earningLog->save();
                        }
                    }
                }
            }

            $ad->clicked += 1;
            $ad->update();

            $analytic = Analytic::firstOrNew([
                'country' => @$query->country_name,
                'advertiser_id' => $ad->advertiser_id,
                'ad_id' => $ad->id
            ]);

            $analytic->ad_title = $ad->ad_title;
            $analytic->click_count += 1;
            $analytic->save();

            return redirect($ad->redirect_url);
        } else {
            return redirect(url('/'));
        }
    }

    public function thirdPartyadClicked(HttpRequest $request)
    {
        $publisherId = $request->input('publisherId');
        $adTypeId = $request->input('adTypeId');
        $IPHash = $request->input('IPHash');
        $currentUrl = $request->input('currentUrl');
        $IPHash = $request['IPHash'];
        $IP = $request['IP'];
        $IP = $request->input('IP');
        $calculatedHash = hash('sha256', $IP);
        if ($IPHash == $calculatedHash) {
            $domain = DomainVerifcation::where('name', $currentUrl)
                ->where('publisher_id', $publisherId)
                ->where('status', 1)->first();

            if (!$domain) {
                info("Domain not found or unverified");
                return;
            }
            $ad = AdType::where('id', $adTypeId)->first();
            $setting = gs();
            // $query = getIpInfo();
            // $query = json_decode(file_get_contents('http://api.ipstack.com/' . $this->getIp() . '?access_key=' . $setting->location_api));
            $publisherId = Crypt::decryptString($publisherId);

            $existingIp = IpChart::where('ip', $IP)->first();
            $publisher = Publisher::findOrFail($publisherId);

            $existIpLog = $existingIp->iplogs
                ->where('ad_type_id', $ad->id)
                ->where('is_click', 1)
                ->where('time', '>=', Carbon::now()->subMinutes(1))
                ->first();

            if ($ad) {
                $publisherAd = PublisherAd::firstOrNew([
                    'ad_type_id' => $ad->id,
                    'publisher_id' => $publisher->id,
                    'date' => Carbon::now()->toDateString()
                ]);

                // $publisherAd->advertiser_id = $ad->advertiser_id;
                $publisherAd->click_count += 1;
                $publisherAd->save();

                if ($ad->is_click == 1) {
                    if (!$existIpLog) {
                        $ipLog = new IpLog();
                        $ipLog->ip_id = $existingIp->id;
                        $ipLog->country = "Unknown";
                        $ipLog->ad_type_id = $ad->id;
                        $ipLog->ad_type = $ad->type;
                        $ipLog->is_click = $ad->is_click;
                        $ipLog->time = Carbon::now()->toTimeString();
                        $ipLog->save();


                        $ipcart = IpLog::with('ip')->where('ad_type_id', $ad->id)->where('is_click', 1)->where('created_at', '>=', Carbon::now()->subHours(24))->get();
                        $uniqueIps = $ipcart->pluck('ip.ip')->count();
                        if ($uniqueIps > $setting->same_ip_limit) {
                        } else {
                            if ($publisher) {
                                $publisher->balance += $ad->tpCost->cpc_pub / $setting->cost_unit;
                                $publisher->update();
                                $earningLog = EarningLog::where('publisher_id', $publisher->id)
                                    ->where('ad_type', $ad->type)
                                    ->where('ad_type_id', $ad->id)
                                    ->where('created_at', '>=', Carbon::now()->subHours(24))
                                    ->first();
                                if (!$earningLog) {
                                    $earningLog = EarningLog::create(
                                        [
                                            'publisher_id' => $publisher->id,
                                            'ad_type_id' => $ad->id,
                                            'ad_type' => $ad->type,
                                            'amount' => $ad->tpCost->cpc_pub / $setting->cost_unit
                                        ]
                                    );
                                } else {
                                    $earningLog->amount += $ad->tpCost->cpc_pub / $setting->cost_unit;
                                    $earningLog->save();
                                }
                            }
                        }
                    }
                }

                return;
            } else {
                return;
            }
        } else {
            return response()->json(['message' => 'Hashes do not match'], 400);
        }
    }
    public function setErrorLog($message, $errors = [])
    {
        info($message, $errors);
    }
}
