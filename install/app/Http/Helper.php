<?php

use App\Models\AppSetting;
use App\Models\MapApiKey;
use Carbon\Carbon;

if (!function_exists('getDistance')) {
    function getDistance(array $firstLatLng, array $secondLatLng): float
    {
        if (empty($firstLatLng) || empty($secondLatLng)) {
            return 0;
        }

        $theta = ($firstLatLng[1] - $secondLatLng[1]);
        $dist = sin(deg2rad($firstLatLng[0])) *
            sin(deg2rad($secondLatLng[0])) +
            cos(deg2rad($firstLatLng[0])) *
            cos(deg2rad($secondLatLng[0])) *
            cos(deg2rad($theta));

        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        return $miles * 1.609344;
    }
}

if (!function_exists('parse')) {
    function parse($date, $format)
    {
        return Carbon::parse($date)->format($format);
    }
}

if (!function_exists('currencyPosition')) {
    function currencyPosition($amount)
    {
        $setting = AppSetting::first();
        $currency = $setting?->currency ?? '$';
        $position = $setting?->currency_position;
        if ($position == 'suffix') {
            return $amount . $currency;
        }

        return $currency . $amount;
    }
}

if (!function_exists('mapApiKey')) {
    function mapApiKey()
    {
        $mapApiKey = MapApiKey::first();
        return $mapApiKey?->key ?? '';
    }
}
