<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localisation extends Model
{
    use HasFactory;
    protected $table = 'localisations';

    protected $fillable = ['lat','lng', 'city', 'adresse'];

}
