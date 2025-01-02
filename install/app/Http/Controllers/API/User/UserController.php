<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ProfilePhotoRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show()
    {
        $user = (new UserRepository())->find(auth()->id());

        return $this->json('Profile details', [
            'user' => (new UserResource($user)),
        ]);
    }

    public function update(UserRequest $request)
    {
        $user = (new UserRepository())->updateByRequest($request, auth()->user());

        return $this->json('Profile is updated successfully', [
            'user' => (new UserResource($user)),
        ]);
    }

    public function updateProfilePhoto(ProfilePhotoRequest $request)
    {
        $user = (new UserRepository())->updateProfilePhotoByRequest($request, auth()->user());

        return $this->json('Profile photo is updated successfully', [
            'user' => (new UserResource($user)),
        ]);
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        $userRepop = new UserRepository();
        $user = $userRepop->find(auth()->id());

        if (Hash::check($request->current_password, $user->password)) {
            (new UserRepository())->update($user, [
                'password' => Hash::make($request->password),
            ]);

            return $this->json('Password is changed successful');
        }

        return $this->json('Sorry, Your Courrent password is not match.');
    }
}
