<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SMS
{
    public static function sendSms($mobile, $message)
    {
        $mobile = substr($mobile, strpos($mobile, '01'));
        $mobile = '88'.$mobile;

        if (config('app.sms_base_url') && config('app.sms_user_name') && config('app.sms_password') && config('app.sms_source')) {
            $res = Http::get(config('app.sms_base_url').'?username='.config('app.sms_user_name').'&password='.config('app.sms_password').'&type=0&dlr=1&source='.config('app.sms_source').'&destination='.$mobile.'&message='.$message);

            return $res;
        }
    }
}
