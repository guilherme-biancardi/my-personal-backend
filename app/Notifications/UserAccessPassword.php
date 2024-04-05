<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserAccessPassword extends Notification
{
    use Queueable;

    public string $password;
    public string $name;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->password = $user->getAuthPassword();
        $this->name = $user->name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('Conta Ativada')
            ->line("Olá **{$this->name}**! Sua conta foi ativada com sucesso")
            ->line("Acesse sua nova conta abaixo, a senha temporária corresponde aos seis primeiros dígitos do seu CPF")
            ->action('Acessar MyPersonal', env('VITE_FRONT_END_URL'))
            ->line('Obrigado por usar nossa aplicação!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
