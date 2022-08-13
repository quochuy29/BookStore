<?php

namespace App\Http\Service;

use App\Models\AuthorProduct;

class AuthorProductsService
{
    public function createAuthor($arr = [], $id = null)
    {
        foreach ($arr as $ar) {
            $authorProduct = new AuthorProduct();
            $authorProduct->product_id = $id;
            $authorProduct->author_id = $ar;
            $authorProduct->save();
        }
    }

    public function EditAuthor($author = [], $arrayAuthorPro = [], $id)
    {
        if (!empty(array_diff($author, $arrayAuthorPro))) {
            if (count($author) == count($arrayAuthorPro)) {

                $perMerge = array_merge($arrayAuthorPro, $author);
                $perUnique = array_unique($perMerge);
                $perDiffKey = array_diff_key($perMerge, $perUnique);
                $perDiff = array_values(array_diff($perUnique, $perDiffKey));

                $perDelete = AuthorProduct::where('product_id', $id)
                    ->where('author_id', $perDiff[0])
                    ->delete();

                $authorProduct = new AuthorProduct();
                $authorProduct->product_id = $id;
                $authorProduct->author_id = $perDiff[1];
                $authorProduct->save();
            } else {
                foreach (array_diff($author, $arrayAuthorPro) as $item) {
                    $authorProduct = new AuthorProduct();
                    $authorProduct->product_id = $id;
                    $authorProduct->author_id = $item;
                    $authorProduct->save();
                }
            }
        } else {
            foreach (array_diff($arrayAuthorPro, $author) as $item) {
                $arrayAuthorPro = AuthorProduct::where('product_id', $id)
                    ->where('author_id', $item)
                    ->delete();
            }
        }
    }
}
