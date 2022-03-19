<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Genres;
use App\Models\Products;
use Illuminate\Http\Request;

class GenresController extends Controller
{
    public function index($id)
    {
        $idGenres = [];
        $model = Products::with('genresProducts')->find($id);
        foreach ($model->genresProducts as $item) {
            array_push($idGenres, $item->genres_id);
        }
        $genres = Genres::whereIn('id', $idGenres)->get();
        return $genres;
    }

    public function listGenres()
    {
        return Genres::get();
    }
}
