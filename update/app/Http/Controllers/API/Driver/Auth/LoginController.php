<?php

namespace App\Http\Controllers\API\Driver\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\DriverRequest;
use App\Http\Requests\SellerLoginRequest;
use App\Http\Resources\UserResource;
use App\Models\DriverDeviceKey;
use App\Repositories\DriverDeviceKeyRepository;
use App\Repositories\DriverRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(SellerLoginRequest $request)
    {
        if ($user = $this->authenticate($request)) {

            if (!$user->is_active) {
                return $this->json('Your account is not active. please contact the admin', [], Response::HTTP_BAD_REQUEST);
            }

            if ($key = $request->device_key) {
                if (!$this->findByKey($key)) {
                    DriverDeviceKey::create([
                        'driver_id' => $user->driver->id,
                        'key' => $key,
                    ]);
                }
            }

            return $this->json('Log In Successfull', [
                'user' => new UserResource($user),
                'access' => (new UserRepository)->getAccessToken($user),
            ]);
        }

        return $this->json('Credential is invalid!', [], Response::HTTP_BAD_REQUEST);
    }


    public function register(DriverRequest $request)
    {
        $user = (new UserRepository())->registerUser($request);

        (new DriverRepository())->storeByUser($user);

        $user->update([
            'is_active' => false,
        ]);

        return $this->json('Rider created successfully', [
            'rider' => UserResource::make($user)
        ]);
    }

    private function authenticate($request)
    {
        $user = (new UserRepository)->findByContact($request->contact);
        if (!is_null($user) && $user->driver) {
            if (Hash::check($request->password, $user->password)) {
                return $user;
            }
        }
        return false;
    }

    public function findByKey($key)
    {
        return DriverDeviceKey::where('key', $key)->first();
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = auth()->user();
        $currentPassword = $request->current_password;

        if (Hash::check($currentPassword, $user->password)) {

            if (Hash::check($request->password, $user->password)) {
                return $this->json('New password cannot be same as current password', [], Response::HTTP_BAD_REQUEST);
            }

            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return $this->json('Password change successfully', [
                'user' => (new UserResource($user)),
            ]);
        }
        return $this->json('Current password is incorrect', [], Response::HTTP_BAD_REQUEST);
    }

    public function show()
    {
        $user = auth()->user();

        return $this->json('user details', [
            'user' => new UserResource($user),
        ]);
    }

    public function logout()
    {
        $user = auth()->user();
        if (\request()->device_key) {
            (new DriverDeviceKeyRepository())->destroy(\request()->device_key);
        }
        if ($user) {
            $user->token()->revoke();

            return $this->json('Logged out successfully!');
        }

        return $this->json('No Logged in user found', [], Response::HTTP_UNAUTHORIZED);
    }
}
