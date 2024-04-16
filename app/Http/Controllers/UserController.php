<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\UploadPhotoRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    public function me()
    {
        $user = Auth::user();
        return $this->setResponseWithResource(new UserResource($user));
    }

    public function activate($hash)
    {
        $user = User::where('remember_token', $hash)->firstOrFail();
        $userService = new UserService($user);

        $userService->activate();

        return response(__('messages.user.activated_sucess'));
    }

    public function sendActivationLink(Request $request)
    {
        try {
            $data = $request->validate(['email' => 'required|email']);
        } catch (ValidationException $th) {
            return response()->json($th->validator->errors(), 400);
        }

        /* Enviaremos o link de ativação ao usuário somente se for um e-mail que existe em nosso banco de dados.
        * Ignoraremos e-mails inexistentes por motivos de segurança.
        * Fazendo assim não revelamos quais emails temos no app */
        try {
            $user = User::where($data)->firstOrFail();

            // Primeiro verificamos se o usuário já está ativo
            if ($user->active) {
                return $this->setResponse(__('messages.user.already_active'), 403);
            }

            $userService = new UserService($user);
            $userService->sendActivationLink();

            return $this->setResponse(__('messages.user.link_sent'));
        } catch (Exception $e) {
            return $this->setResponse(__('messages.user.link_sent'));
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $response = Password::sendResetLink(
                $this->validate($request, ['email' => 'required|email'])
            );
        } catch (ValidationException $th) {
            return $th->validator->errors();
        }

        if ($response == Password::RESET_LINK_SENT || $response == Password::INVALID_USER) return $this->setResponse(__('messages.user.link_sent'));

        return $this->setResponse($response, 400);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::user();
        $userService = new UserService($user);

        try {
            $userService->updatePassword($validated['current_password'], $validated['password']);

            return $this->setResponse(__('messages.user.password.success'));
        } catch (Exception $err) {
            return $this->setResponse($err->getMessage(), 400);
        }
    }

    public function uploadPhoto(UploadPhotoRequest $request)
    {
        $user = Auth::user();
        $image = $request->file('image');

        if ($image) {
            $userService = new UserService($user);
            $userService->uploadPhoto($image);

            return $this->setResponseWithResource(new UserResource($user), __('messages.user.photo.success'));
        }

        return $this->setResponse(__('messages.user.photo.failure'), 400);
    }
}
