<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\tags;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index($id)
    {
        return tags::where('id',$id)->get();
    }

    public function tag($slug)
    {
        return tags::where('slug',$slug)->first();
    }
}
