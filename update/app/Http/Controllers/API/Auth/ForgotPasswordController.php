<?php

namespace App\Http\Controllers\API\Auth;

use App\Events\UserMailEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordOtpVerifyRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Repositories\UserRepository;
use App\Repositories\VerificationCodeRepository;
use App\Services\SMS;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    /**
     * @var VerificationCodeRepository
     */
    private $verificationCodeRepo;

    private $userRepo;

    public function __construct(VerificationCodeRepository $verificationCodeRepo, UserRepository $userRepo)
    {
        $this->verificationCodeRepo = $verificationCodeRepo;
        $this->userRepo = $userRepo;
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $contact = $request->{$request->type};
        $user = $this->userRepo->findByContact($contact);

        if (! $user) {
            return $this->json('Sorry! No user found with this mobile.', [], Response::HTTP_BAD_REQUEST);
        }

        $verificationCode = $this->verificationCodeRepo->findOrCreateByContact($contact);

        if ($request->mobile) {
            $message = 'Hello '.$user->name."\nYour password reset OTP is ".$verificationCode->otp;
            SMS::sendSms($request->mobile, $message);

            return $this->json('We sent otp to your phone', [
                'otp' => $verificationCode->otp,
            ]);
        } else {
            UserMailEvent::dispatch($user, $verificationCode->otp);

            return $this->json('We sent otp to your email', [
                'otp' => $verificationCode->otp,
            ]);
        }
    }

    public function resendOTP(ForgotPasswordRequest $request)
    {
        $contact = $request->{$request->type};
        $user = $this->userRepo->findByContact($contact);

        if ($user) {
            $verificationCode = $this->verificationCodeRepo->findOrCreateByContact($contact);

            if ($request->mobile) {
                $message = 'Hello '.$user->name."\nYour password reset otp is ".$verificationCode->otp;
                SMS::sendSms($request->mobile, $message);
            } else {
                UserMailEvent::dispatch($user, $verificationCode->otp);
            }

            return $this->json('Verification code is resent success to your email', [
                'otp' => $verificationCode->otp,
            ]);
        }

        return $this->json('Sorry, your contact is not found!');
    }

    public function verifyOtp(ForgotPasswordOtpVerifyRequest $request)
    {
        $contact = $request->{$request->type};
        $user = $this->userRepo->findByContact($contact);

        if (! $user) {
            return $this->json('Sorry! No user found with this email.', [], Response::HTTP_BAD_REQUEST);
        }

        $verificationCode = $this->verificationCodeRepo->checkCode($contact, $request->otp);

        if (! $verificationCode) {
            return $this->json('Invalid OTP', [], Response::HTTP_BAD_REQUEST);
        }

        return $this->json('Otp matched successfully!', [
            'token' => $verificationCode->token,
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $verifyCode = $this->verificationCodeRepo->checkByToken($request->token);

        if (! $verifyCode) {
            return $this->json('Invalid token', [], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userRepo->findByContact($verifyCode->contact);

        if (! $user) {
            return $this->json('Sorry! No user found with this mobile.', [], Response::HTTP_BAD_REQUEST);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $verifyCode->delete();

        return $this->json('Password reset successfully!');
    }
}
