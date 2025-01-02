<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SMSGatewaySetupController extends Controller
{
    public function index()
    {
        return view('sms-gateway.index');
    }

    public function update(Request $request)
    {

        try {
            $this->setEnv('SMS_BASE_URL', $request->url);
            $this->setEnv('SMS_USER_NAME', $request->user_name);
            $this->setEnv('SMS_PASSWORD', $request->password);
            $this->setEnv('SMS_SOURCE', $request->source);
            $this->setEnv('SMS_TWO_STEP_VERIFACATION', $request->two_step_verification ? true : false);

            Artisan::call('config:clear');

            return back()->with('success', 'SMS configuration is setuped successful.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
