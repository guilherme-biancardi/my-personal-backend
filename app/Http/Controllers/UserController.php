<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Resources\UserResource;
use App\Jobs\UserActivationLink;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    public function me(Request $request)
    {
        $user = Auth::user($request->bearerToken());
        return $this->setResponseWithResource(new UserResource($user));
    }

    public function activate($hash)
    {
        $user = User::where('remember_token', $hash)->firstOrFail();

        abort_unless($user->isWaitingForActivation(), 403, __('messages.user.link_expired'));

        $user->activate();

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

            dispatch(new UserActivationLink($user));
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

    public function changePassword(ChangePasswordRequest $request){
        $user = Auth::user();
        $validated = $request->validated();

        if(!Hash::check($validated['current_password'], $user->getAuthPassword())) {
            return $this->setResponse(__('messages.user.password.current_fail'), 400);
        }

        if($validated['password'] === $validated['current_password']) {
            return $this->setResponse(__('messages.user.password.equals'), 400);
        }

        $user->changePassword($validated['password']);

        return $this->setResponse(__('messages.user.password.success'));
    }
}
