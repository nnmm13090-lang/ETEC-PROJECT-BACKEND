<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'content', 'excerpt',
        'cover_image', 'status', 'user_id', 'category_id',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Auto-generate slug on create
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->slug = $post->slug ?: Str::slug($post->title);
        });
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    // Alias so blade can call $post->categories->first()
    public function categories()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getReadTimeAttribute(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($this->content)) / 200));
    }

    public function getViewsCountAttribute(): int
    {
        return $this->views ?? 0;
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}