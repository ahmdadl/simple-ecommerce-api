<?php

namespace Modules\Orders\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Orders\Models\Order;

class OrderStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Order $order)
    {
        $this->order->loadMissing([
            "user",
            "items.product",
            "shippingAddress.city",
        ]);
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
            ->subject(
                __("orders::t.mail.subject." . $this->order->status->value)
            )
            ->view("orders::emails.order_updated", [
                "order" => $this->order,
                "user" => $notifiable,
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            "message" =>
                "Your order status has been updated to " .
                $this->order->status->localized(),
            "order_id" => $this->order->id,
            "type" => "order_status_updated",
        ];
    }

    public function toDatabase($notifiable): array
    {
        return [
            "status" => $this->order->status->localized(),
            "order_id" => $this->order->id,
            "type" => "order_status_updated",
        ];
    }
}
