<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Authors;
use App\Models\Products;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index($id)
    {
        $idAuthor = [];
        $model = Products::with('authorProducts')->find($id);
        foreach ($model->authorProducts as $item) {
            array_push($idAuthor, $item->author_id);
        }
        $author = Authors::whereIn('id', $idAuthor)->get();
        return $author;
    }
}
