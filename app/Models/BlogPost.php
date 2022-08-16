<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

/**
 * @property mixed id
 */
class BlogPost extends Model implements Feedable
{
    protected $table = 'blog_posts';
    protected $fillable = ['title', 'summary', 'category_id', 'picture_id', 'text', 'author_id', 'views', 'status', 'language'];
    protected $dates = ['created_at'];
    protected $appends = ['api_url'];
    protected $casts = [
        'draft' => 'boolean'
    ];

    public function picture()
    {
        return $this->belongsTo(Media::class, 'picture_id');
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function category()
    {
        return $this->belongsTo(BlogPostCategory::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }


    public function tags()
    {
        return $this->hasMany(BlogPostTag::class, 'post_id', 'id');
    }

    public function getApiUrlAttribute()
    {
        return URL::route('post_api_url', ['id' => $this->id]);
    }

    public function toFeedItem()
    {
        return FeedItem::create([
            'id' => $this->id,
            'title' => $this->title,
            'summary' => $this->summary,
            'updated' => $this->updated_at,
            'link' => Route('blog_post_single', ['id' => $this->id]),
            'author' => $this->author->full_name,
        ]);
    }

    public static function getFeedItems()
    {
        return BlogPost::where('status', 'published')->get();
    }
}
