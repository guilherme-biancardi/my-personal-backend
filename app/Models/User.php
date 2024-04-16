<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserType;
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
        'image',
        'cpf',
        'type'
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
        return $this->type === UserType::OWNER->value;
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
        $this->active = true;
        $this->activated_at = now();
        $this->save();
    }

    public function getPhoto() : string | null
    {
        if ($this->image && Storage::exists($this->image)) {
            return Storage::url($this->image);
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
