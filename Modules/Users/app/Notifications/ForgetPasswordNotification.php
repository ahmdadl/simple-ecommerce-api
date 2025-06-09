<?php

namespace Modules\Users\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ForgetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $token)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(mixed $notifiable): array
    {
        return ["mail"];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(__("Reset Password Notification"))
            ->line(
                __(
                    "You are receiving this email because we received a password reset request for your account."
                )
            )
            ->line(__("use the following code to reset your password"))
            ->line($this->token)
            ->line(
                __("This code will expire in :count minutes.", [
                    "count" => 5,
                ])
            )
            ->line(
                __(
                    "If you did not request a password reset, no further action is required."
                )
            );
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(mixed $notifiable): array
    {
        return [];
    }
}
