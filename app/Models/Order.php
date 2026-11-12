<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'pincode',
        'total',
        'mrp_total',
        'delivery_fee',
        'discount',
        'coupon_code',
        'payment_method',
        'prescription_path',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'mrp_total' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'discount' => 'decimal:2',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
