<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    use HasFactory;

    // Define the table name (optional, as Laravel will automatically use the plural of the model name)
    protected $table = 'movies';

    // Define the fillable attributes (columns you want to mass assign)
    protected $fillable = [
        'title',
        'backdrop_path',
        'genres',
        'overview',
        'poster_path',
        'release_date',
    ];
}