<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $casts = [
        'items' => 'json'
    ];
    protected $fillable = [
        'order_id', 'email', 'subtotal', 'odate', 'items','quantity',
    ];
}
