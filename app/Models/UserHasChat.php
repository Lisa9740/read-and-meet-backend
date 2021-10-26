<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHasChat extends Model
{
    use HasFactory;

    protected $table = 'user_has_chats';

    protected $fillable = ['user_id', 'chat_id'];

    public function chats(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Chat::class, 'chats');
    }

    public function users()
    {
        return $this->morphToMany(User::class, 'users');
    }

}
