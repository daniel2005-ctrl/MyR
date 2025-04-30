<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('🔒 Restablece tu contraseña en MYR Proyectos')
            ->greeting("¡Hola {$notifiable->nombre}!")
            ->line('Recibimos una solicitud para cambiar tu contraseña.')
            ->action('Restablecer contraseña', $url)
            ->line('Si no fuiste tú, simplemente ignora este mensaje.')
            ->salutation('Saludos,
            El equipo de MYR Proyectos');
    }
}
