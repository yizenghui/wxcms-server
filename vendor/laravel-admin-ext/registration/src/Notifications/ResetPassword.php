<?php

namespace Encore\Admin\Registration\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends ResetPasswordNotification
{
    /**
     * {@inheritdoc}
     */
    public function toMail($notifiable)
    {
        $view = config(
            'admin.registration.views.notifications.reset_password',
            'registration::notifications.reset-password'
        );

        $data = [
            'url' => url(config('app.url').route('admin.password.reset', $this->token, false)),
        ];

        return (new MailMessage())
            ->subject(trans('registration.reset_password'))
            ->view($view, $data);
    }
}