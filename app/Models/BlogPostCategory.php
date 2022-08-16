<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPostCategory extends Model
{
    protected $table = 'blog_post_categories';
    protected $fillable = ['name_fa', 'name_en', 'parent_id'];

    public function posts()
    {
        return $this->hasMany(BlogPost::class, 'category_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(BlogPostCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(BlogPostCategory::class, 'parent_id', 'id');
    }
}
