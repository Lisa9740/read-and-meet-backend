<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;
    protected $table = 'order_items';
    protected $fillable = ['order_id', 'product_id', 'quantity'];

    public function order(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OrderDetails::class, 'id', 'order_id');
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}


