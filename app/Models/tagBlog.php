<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tagBlog extends Model
{
    use HasFactory;
    public $table = "tag_blog";
    public $fillable = ['tag_id','blog_id'];

    public function tag()
    {
        return $this->hasMany(tags::class,'id','tag_id');
    }
}
