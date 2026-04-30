<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'slug',
        'cover_image',
        'status',
        'category_id',
    ];
}