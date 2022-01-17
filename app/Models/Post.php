<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $fillable = ['title','description','user_id', 'book_id', "localisation_id", "is_visible"];

    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function book(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Book::class, 'id', 'book_id');
    }

    public function localisation(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Localisation::class, 'id', 'localisation_id');
    }

}
