<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $token = Auth::attempt($credentials);

        if (!$token) {
            return $this->setResponse(__('messages.auth.not_credentials'), 401);
        }

        if (Auth::user()->isWaitingForActivation()) {
            Auth::logout();
            return $this->setResponse(__('messages.auth.not_activated'), 403);
        }

        $resource = new JsonResource(['token' => $token]);
        return $this->setResponseWithResource($resource, __('messages.auth.logged_in'));
    }

    public function logout(Request $request)
    {
        Auth::logout($request->bearerToken());
        return $this->setResponse(__('messages.auth.logged_out'));
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:6|confirmed',
            ]);
        } catch (ValidationException $th) {
            return response()->json($th->validator->errors(), 400);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->fill([
                    'password' => $password
                ])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? $this->setResponse(__('messages.user.password.success'))
            : $this->setResponse(__('messages.user.password.failure'), 400);
    }
}
