<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class MailResetPasswordNotification extends Notification
{
    use Queueable;

    protected $pageUrl;

    public $token;

    /**
     * Create a new notification instance.
     *
     * @param $token
     * @return void
     */
    public function __construct($token)
    {
        parent::__construct($token);
        $this->pageUrl = 'localhost:8000';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(Static::$toMailCallback, $notifiable, $this->token);
        }

        return (new MailMessage)
            ->subject(Lang::getFromJson('Recuperar Senha'))
            ->line(Lang::getFromJson('Você está recebendo este email por causa que nós recebemos uma solicitação de recuperação de senha para sua conta.'))
            ->action(Lang::getFromJson('Recuperar Senha'), $this->pageUrl."?token=".$this->token)
            ->line(Lang::getFromJson('Este link de recuperação de senha expira em :count minutos.', ['count' => config('auth.passwords.users.expire')]))
            ->line(Lang::getFromJson('Se você não requesitou a recuperação de senha, nenhuma acão é requerida.'));
    }
}
