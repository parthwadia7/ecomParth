<?php

// app/Notifications/OrderConfirmation.php
namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderConfirmation extends Notification
{
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Order Confirmation')
                    ->greeting('Hello ' . $notifiable->name)
                    ->line('Thank you for your order.')
                    ->line('Order ID: ' . $this->order->id)
                    ->line('Total: $' . $this->order->total)
                    ->action('View Order', url('/orders/' . $this->order->id))
                    ->line('Thank you for shopping with us!');
    }
}
