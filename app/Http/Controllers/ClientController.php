<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    public function index()
    {
        $users = User::where('type', UserType::CLIENT)->get();
        return UserResource::collection($users);
    }

    public function register(RegisterRequest $request)
    {
        $userRequest = $request->validated();

        $userRequest['password'] = UserService::generatePasswordByCPF($userRequest['cpf']);
        $userRequest['remember_token'] = Str::random(60);
        $userRequest['type'] = UserType::CLIENT;

        $user = User::create($userRequest);
        $userService = new UserService($user);

        $userService->sendActivationLink();

        return $this->setResponse(__('messages.client.created'));
    }
}
