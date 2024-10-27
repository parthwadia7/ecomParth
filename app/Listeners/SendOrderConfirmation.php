<?php
// app/Listeners/SendOrderConfirmation.php
namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Notifications\OrderConfirmation;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderConfirmation implements ShouldQueue
{
    public function handle(OrderPlaced $event)
    {
        $event->order->user->notify(new OrderConfirmation($event->order));
    }
}
