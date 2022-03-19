<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\blogs;
use App\Models\tagBlog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        return blogs::get();
    }

    public function detail($slug)
    {
        return blogs::with('tag_blog', 'user')->where('slug', $slug)->first();
    }

    public function tag($id)
    {
        return blogs::with('tag')->where('id', $id)->first();
    }

    public function related($id)
    {
        $model = blogs::where('id', $id)->first();
        return blogs::where('blogCate_id', $model->blogCate_id)
            ->where('id', '!=', $id)
            ->where('updated_at', 'like', '%' . Carbon::parse($model->updated_at)->format('Y-m-d') . '%')
            ->get();
    }

    public function blogTag($slug)
    {
        $tag = tagBlog::join('tags', 'tags.id', '=', 'tag_blog.tag_id')
            ->where('tags.slug', $slug)
            ->pluck('tag_blog.blog_id');
        $blog = blogs::whereIn('id',$tag)->get();
        return $blog;
    }

    public function newBlog()
    {
        return blogs::orderBy('updated_at','desc')->limit(5)->get();
    }
}
