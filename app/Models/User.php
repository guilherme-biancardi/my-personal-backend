<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\UserRecoverPasswordLink;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use RuntimeException;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'remember_token',
        'image'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
        'is_owner' => 'boolean'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'activated_at',
        'password_changed_at'
    ];

    // determine if the user is waiting to activate its account
    public function isWaitingForActivation(): bool
    {
        return !$this->active && !$this->activated_at;
    }

    // determine if the user is waiting to activate its account
    public function isOwner(): bool
    {
        return $this->is_owner;
    }

    // determine if the user is waiting to activate its account
    public function isFirstAccess(): bool
    {
        return $this->password_changed_at === null;
    }

    /**
     * Activate the user.
     *
     */
    public function activate()
    {
        // let's validate the user we are actvating. Just check for safety.
        throw_if($this->active, RuntimeException::class, 'The user is already active');

        $this->active = true;
        $this->activated_at = now();
        $this->save();
    }

    public function getPhotoBase64()
    {
        if ($this->image && Storage::exists($this->image)) {
            $photoContent = Storage::get($this->image);
            $mimeType = Storage::mimeType($this->image);

            $base64code = 'data:' . $mimeType . ';base64,';
            $photo_base64 = base64_encode($photoContent);

            return $base64code . $photo_base64;
        }

        return null;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function changePassword($password)
    {
        $this->password = $password;
        $this->password_changed_at = now();
        $this->save();
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function sendPasswordResetNotification($token)
    {

        $url = env('VITE_FRONT_END_URL') . '/redefinir-senha/' . $token;

        $this->notify(new UserRecoverPasswordLink($url));
    }
}
