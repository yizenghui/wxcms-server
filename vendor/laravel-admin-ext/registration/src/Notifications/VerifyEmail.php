<?php

namespace Encore\Admin\Registration\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;

class VerifyEmail extends Notification
{
    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $view = config(
            'admin.registration.views.notifications.verify_email',
            'registration::notifications.verify-email'
        );

        $data = [
            'email' => $notifiable->email,
            'url'   => $this->verificationUrl($notifiable),
        ];

        return (new MailMessage())
            ->subject(trans('registration.verify_email'))
            ->view($view, $data);
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'admin.verification.verify',
            Carbon::now()->addMinutes(config('admin.registration.verification_expire', 60)),
            ['id' => $notifiable->getKey()]
        );
    }
}