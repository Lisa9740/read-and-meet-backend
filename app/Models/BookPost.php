<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class BookPost extends Model
{
    use HasFactory;

    protected $table = 'bookPost';
    protected $fillable = ['title','description','user_id'];

/*    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }*/

}
