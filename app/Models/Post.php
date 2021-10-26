<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $fillable = ['title','description','user_id', 'book_id', "localisation_id"];

    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id');
    }
    public function book(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Book::class, 'id');
    }

    public function localisation(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Localisation::class, 'id');
    }

}
