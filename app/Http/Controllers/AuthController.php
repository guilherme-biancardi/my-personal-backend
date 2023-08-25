<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    use ResponseTrait;

    public function me(Request $request){
        $user = Auth::user($request->bearerToken());
        return response()->json(new UserResource($user));
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $token = Auth::attempt($credentials);

        if (!$token) {
            return $this->setResponse(['usuário não autorizado', 401]);
        }

        $user = Auth::user($token);

        return $this->setResponseWithResource([
            'user' => $user,
            'token' => $token,
        ], 'usuário logado com sucesso!');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        $token = Auth::login($user);

        return $this->setResponseWithResource([
            'user' => $user,
            'token' => $token,
        ], 'usuário criado com sucesso!', 201);
    }

    public function logout(Request $request)
    {
        Auth::logout($request->bearerToken());
        return $this->setResponse('usuário deslogado com sucesso!');
    }
}
