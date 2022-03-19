<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\tagBlog;
use Illuminate\Http\Request;

class TagBlogController extends Controller
{
    public function index($id)
    {
        return tagBlog::with('tag')->where('tag_id',$id)->get();
    }
}
