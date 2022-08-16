<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPostTag extends Model
{
    protected $table = 'blog_post_tags';
    protected $fillable = ['post_id', 'text'];

    public function post()
    {
        return $this->belongsTo(BlogPost::class, 'post_id');
    }
}
