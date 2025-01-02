<?php

namespace App\Http\Controllers\API\Master;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\MobileAppUrl;

class MasterController extends Controller
{
    public function index()
    {
        $currency = AppSetting::first()?->currency ?? '$';
        $mobileAppLink = MobileAppUrl::first();

        $SMStwoStepVerification = false;
        if (config('app.sms_base_url') && config('app.sms_user_name') && config('app.sms_password') && config('app.sms_source') && config('app.sms_two_step_verification')) {
            $SMStwoStepVerification = true;
        }

        $emailVerify = config('app.mail_two_step_verification') ? true : false;
        $twoStepVerification = $SMStwoStepVerification == true ? true : $emailVerify;
        $deviceType = $SMStwoStepVerification ? 'mobile' : ($emailVerify ? 'email' : null);

        return $this->json('All configuration list', [
            'currency' => $currency,
            'android_url' => $mobileAppLink ? $mobileAppLink->android_url : '',
            'ios_url' => $mobileAppLink ? $mobileAppLink->ios_url : '',
            'two_step_verification' => (bool) $twoStepVerification,
            'device_type' => $deviceType,
            'cash_on_delivery' => (bool) true,
            'online_payment' => (bool) false,
        ]);
    }
}
