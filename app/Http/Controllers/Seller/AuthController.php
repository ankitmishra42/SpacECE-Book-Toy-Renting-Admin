<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\SellerLoginRequest;
use App\Http\Requests\ShopRequest;
use App\Http\Resources\SellerProfileResource;
use App\Repositories\StoreRepository;
use App\Repositories\UserRepository;
use App\Repositories\VerificationCodeRepository;
use App\Services\SMS;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(SellerLoginRequest $request)
    {
        $user = $this->checkAuth($request);

        if (!$user) {
            return $this->json('Credential is invalid!', [], Response::HTTP_BAD_REQUEST);
        } elseif ($user && !$user->is_active) {
            return $this->json('Your account is not active. please contact the admin', [], Response::HTTP_BAD_REQUEST);
        }

        return $this->json('Log In Successfull', [
            'user' => SellerProfileResource::make($user),
            'access' => (new UserRepository())->getAccessToken($user),
        ]);
    }

    private function checkAuth(SellerLoginRequest $request)
    {
        $user = (new UserRepository())->findByContact($request->contact);

        if (!is_null($user) && $user->store) {
            if (Hash::check($request->password, $user->password)) {
                return $user;
            }
        }
    }

    public function register(ShopRequest $request)
    {
        $store = (new StoreRepository())->storeByRequest($request);
        (new StoreRepository())->createSchedule($store);

        $store->user->update([
            'is_active' => false
        ]);

        return $this->json('Shop created successfully', [
            'user' => SellerProfileResource::make($store->user)
        ]);
    }

    public function sendOTP(Request $request)
    {
        $request->validate(['mobile' => 'required|numeric']);

        $verificationCode = (new VerificationCodeRepository())->findOrCreateByContact($request->mobile);

        $message = "Hello Your Seller Registration OTP is " . $verificationCode->otp;
        SMS::sendSms($request->mobile, $message);

        return $this->json('We sent otp to your phone', [
            'otp' => $verificationCode->otp,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $verificationCode = (new VerificationCodeRepository())->checkCode($request->mobile, $request->otp);

        if (! $verificationCode) {
            return $this->json('Invalid OTP', [], Response::HTTP_BAD_REQUEST);
        }
        $verificationCode->delete();
        return $this->json('Otp matched successfully!');
    }
}
