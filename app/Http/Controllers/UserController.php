<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Jobs\UserActivationLink;
use App\Models\User;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    use ResponseTrait;

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
            return $th->validator->errors();
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
}
