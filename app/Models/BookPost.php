<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class BookPost extends Model
{
    use HasFactory;

    protected $table = 'bookpost';
    protected $fillable = ['title','description','user_id', 'book_id'];

    public function user()
    {
        return $this->hasOne(User::class, 'id');
    }
    public function book(){
        return $this->hasOne(Book::class, 'id');
    }

}
