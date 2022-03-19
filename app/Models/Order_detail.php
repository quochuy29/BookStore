<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_detail extends Model
{
    use HasFactory;
    protected $table = "order_detail";

    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'shipping_price',
        'payment_status',
        'delivery_status',
        'quantity'
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

}
