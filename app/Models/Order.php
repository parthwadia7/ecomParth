<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // app/Models/Order.php
    public const STATUS_PENDING = 'Pending';
    public const STATUS_PAID = 'Paid';
    public const STATUS_SHIPPED = 'Shipped';
    public const STATUS_DELIVERED = 'Delivered';
    public const STATUS_CANCELED = 'Canceled';

    public function discounts()
    {
        return $this->morphMany(Discount::class, 'discountable');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }

}
