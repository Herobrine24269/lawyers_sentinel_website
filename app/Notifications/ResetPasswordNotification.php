<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a notification instance.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

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
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        $expire = config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);

        return (new MailMessage)
            ->subject(config('app.name', 'Juris Sentinel') . ' - Password Reset Request')
            ->greeting('Hello ' . ($notifiable->name ?? 'User') . ',')
            ->line('You are receiving this email because we received a password reset request for your Juris Sentinel account.')
            ->action('Reset Password', $url)
            ->line('This password reset link will expire in ' . $expire . ' minutes.')
            ->line('Security Notice: If you did not request a password reset, no further action is required. Your account remains completely secure.')
            ->salutation('Regards, ' . config('app.name', 'Juris Sentinel') . ' Legal Team');
    }
}
