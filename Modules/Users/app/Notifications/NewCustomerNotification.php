<?php

namespace Modules\Users\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewCustomerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ["mail", "database"];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(__("users::t.mail.welcome_subject"))
            ->greeting(
                __("users::t.mail.greeting", ["name" => $notifiable->name])
            )
            ->line(__("users::t.mail.intro_line"))
            ->action(__("users::t.mail.login_button"), frontUrl("login"))
            ->line(__("users::t.mail.outro_line"))
            ->salutation(
                __("users::t.mail.signature") . ", " . config("app.name")
            );
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            "title" => __("users::t.mail.welcome_subject"),
            "message" => __("users::t.mail.intro_line"),
            "action_url" => frontUrl("login"),
        ];
    }
}
