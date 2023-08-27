<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Jobs\UserActivationLink;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class AuthController extends Controller
{

    use ResponseTrait;

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $token = Auth::attempt($credentials);

        if (!$token) {
            return $this->setResponse(__('messages.auth.not_authorized'), 401);
        }

        if (Auth::user()->isWaitingForActivation()) {
            Auth::logout();

            return $this->setResponse(__('messages.auth.not_activated'), 500);
        }

        $resource = new JsonResource(['token' => $token]);
        return $this->setResponseWithResource($resource, __('messages.auth.logged_in'));
    }

    public function register(RegisterRequest $request)
    {
        $userRequest = $request->validated();
        $userRequest['remember_token'] = Str::random(60);

        $user = User::create($userRequest);

        dispatch(new UserActivationLink($user));

        return $this->setResponse(__('messages.auth.created'), 201);
    }

    public function logout(Request $request)
    {
        Auth::logout($request->bearerToken());
        return $this->setResponse(__('messages.auth.logged_out'));
    }
}
