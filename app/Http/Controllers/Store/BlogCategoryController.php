<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\blogCategories;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public function index()
    {
        return blogCategories::get();
    }
}
