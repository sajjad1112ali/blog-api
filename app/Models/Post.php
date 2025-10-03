<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Auth;

class Post extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['title', 'slug', 'content', 'user_id', 'published_at', 'image', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_post')->withTimestamps();
    }


    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function likes()
    {
        return $this->reactions()->where('type', 'like');
    }

    public function dislikes()
    {
        return $this->reactions()->where('type', 'dislike');
    }

    public function scopeWithReactionCounts($query)
    {
        return $query->withCount([
            'reactions as likes_count'    => fn($q) => $q->where('type', 'like'),
            'reactions as dislikes_count' => fn($q) => $q->where('type', 'dislike'),
            'reactions as loves_count'    => fn($q) => $q->where('type', 'love'),
            'reactions as hahas_count'    => fn($q) => $q->where('type', 'haha'),
            'reactions as wows_count'     => fn($q) => $q->where('type', 'wow'),
            'reactions as sads_count'     => fn($q) => $q->where('type', 'sad'),
            'reactions as angrys_count'   => fn($q) => $q->where('type', 'angry'),
        ]);
    }
    public function scopeWithMyReaction($query)
    {
        return $query->with(['reactions' => fn($q) => $q->where('user_id', Auth::id())]);
    }
    public function getPublishedAtFormatedAttribute($value)
    {
        return Carbon::parse($value)->format('M d, Y');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('poster')
            ->useDisk('media') // 👈 store files on custom disk
            ->singleFile(); // optional if you want only one poster
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->height(232)
            ->sharpen(10)
            ->nonQueued();
    }
}
