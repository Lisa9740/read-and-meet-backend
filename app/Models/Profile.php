<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';
    protected $fillable = ['user_id', 'description', 'book_liked', 'photo'];

    public function user()
    {
        return $this->hasOne(Profile::class, 'id');
    }
}
