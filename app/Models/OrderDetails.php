<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;
    protected $table = 'order_details';
    protected $fillable = ['user_id', 'total'];

    public function payment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasOne(Message::class, 'id', 'chat_id');
    }
}


