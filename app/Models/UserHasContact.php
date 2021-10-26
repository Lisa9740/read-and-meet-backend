<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHasContact extends Model
{
    use HasFactory;

    protected $table = 'user_has_contacts';

    protected $fillable = ['user_id', 'contact_id'];

    public function contacts(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Contact::class, 'contacts');
    }

    public function users()
    {
        return $this->morphToMany(User::class, 'users');
    }



}
