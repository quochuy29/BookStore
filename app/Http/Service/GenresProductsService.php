<?php

namespace App\Http\Service;

use App\Models\GenresProduct;

class GenresProductsService
{
    public function createGenresProduct($arr = [], $id = null)
    {
        foreach ($arr as $ar) {
            $genresProduct = new GenresProduct();
            $genresProduct->product_id = $id;
            $genresProduct->genres_id = $ar;
            $genresProduct->save();
        }
    }

    public function EditGenresProduct($genres = [], $arrayGenPro = [], $id)
    {
        if (!empty(array_diff($genres, $arrayGenPro))) {
            if (count($genres) == count($arrayGenPro)) {

                $perMerge = array_merge($arrayGenPro, $genres);
                $perUnique = array_unique($perMerge);
                $perDiffKey = array_diff_key($perMerge, $perUnique);
                $perDiff = array_values(array_diff($perUnique, $perDiffKey));

                $perDelete = GenresProduct::where('product_id', $id)
                    ->where('genres_id', $perDiff[0])
                    ->delete();

                $genresProducts = new GenresProduct();
                $genresProducts->product_id = $id;
                $genresProducts->genres_id = $perDiff[1];
                $genresProducts->save();
            } else {
                foreach (array_diff($genres, $arrayGenPro) as $item) {
                    $genresProducts = new GenresProduct();
                    $genresProducts->product_id = $id;
                    $genresProducts->genres_id = $item;
                    $genresProducts->save();
                }
            }
        } else {
            foreach (array_diff($arrayGenPro, $genres) as $item) {
                $arrayGenPro = GenresProduct::where('product_id', $id)
                    ->where('genres_id', $item)
                    ->delete();
            }
        }
    }
}
