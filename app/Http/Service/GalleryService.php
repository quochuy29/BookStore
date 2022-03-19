<?php

namespace App\Http\Service;

use App\Models\Galleries;

class GalleryService
{
    public function createGallery($arrFile = [], $arrRemoveImg = null, $id = null)
    {
        $strIds = rtrim($arrRemoveImg, ',');
        $lstIds = explode(',', $strIds);
        $arr = array_diff($lstIds, $arrFile);
        foreach ($arr as $item) {
            unset($arrFile[$item]);
        }

        foreach ($arrFile as $key => $gallery) {
            if ($item !== $key) {
                $uploadImg = $gallery->store('galleries/products/', 'public', uniqid() . '-' . $gallery->getClientOriginalName());

                $galleries = new Galleries();
                $galleries->product_id = $id;
                $galleries->url_image = $uploadImg;
                $galleries->save();
            }
        }
    }
}
