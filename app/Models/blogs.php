<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class blogs extends Model
{
    use HasFactory;

    public $table = "blogs";
    public $fillable = ['title', 'description', 'blogCate_id','slug'];

    public function tag_blog()
    {
        return $this->hasMany(tagBlog::class, 'blog_id');
    }

    public function blog_cate()
    {
        return $this->hasOne(blogCategories::class, 'id', 'blogCate_id');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function tag()
    {
        return $this->belongsToMany(tags::class, 'tag_blog', 'tag_id', 'blog_id');
    }
}
