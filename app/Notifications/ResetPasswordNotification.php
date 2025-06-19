<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Footer;

class ResetPasswordNotification extends Notification
{
    use Queueable;

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
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        // Obtener datos del footer para las redes sociales
        $footer = Footer::first();

        return (new MailMessage)
            ->subject('🏠 Restablecer Contraseña - MYR Proyectos')
            ->view('emails.reset-password', [
                'notifiable' => $notifiable,
                'resetUrl' => $resetUrl,
                'footer' => $footer
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}