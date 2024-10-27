<?php
// app/Notifications/LowStockAlert.php
namespace App\Notifications;

use App\Models\Product;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LowStockAlert extends Notification
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Low Stock Alert')
                    ->line("Product {$this->product->name} is running low on stock.")
                    ->line("Current stock: {$this->product->stock}")
                    ->action('Manage Stock', url('/admin/products/' . $this->product->id));
    }
}
