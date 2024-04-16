<?php

namespace App\Services;

use App\Http\Requests\User\ChangePasswordRequest;
use App\Jobs\UserActivationLink;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public static function generatePasswordByCPF(string $cpf, int $length = 6): string
    {
        $cpfOnlyNumbers = preg_replace('/[\.-]/', '', $cpf);
        return substr($cpfOnlyNumbers, 0, $length);
    }

    public function sendActivationLink(): PendingDispatch
    {
        $activationLink = new UserActivationLink($this->user);
        return dispatch($activationLink);
    }

    public function activate()
    {
        abort_unless($this->user->active, 403, __('messages.user.already_active'));
        $this->user->activate();
    }

    public function updatePassword(string $current_password, string $new_password)
    {
        if (!Hash::check($current_password, $this->user->getAuthPassword())) {
           return throw new Exception(__('messages.user.password.current_fail'));
        }

        if ($current_password === $new_password) {
            return throw new Exception(__('messages.user.password.equals'));
        }

        $this->user->changePassword($new_password);
    }

    public function uploadPhoto(UploadedFile $image)
    {
        $imagePath = $this->user->image;

        if ($imagePath && Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }

        $path = $image->store('users');

        $this->user->update([
            'image' => $path
        ]);

        $this->user->save();
    }
}
