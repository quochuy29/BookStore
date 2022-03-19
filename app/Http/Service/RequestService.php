<?php

namespace App\Http\Service;

use App\Models\Categories;

class RequestService
{
    public function createRequest($request)
    {
        $url = "?";
        if (isset($request['search'])) {
            $url .= 'search=' . $request['search'] . '&';
        }

        $sortBy = isset($request['sortBy']) && $request['sortBy'] != '' ? $url .= 'sortBy=' . $request['sortBy'] . '&' : '';

        if (isset($request['priceMax'])) {
            if ($request['priceMax'] > 0) {
                $url .= 'priceMax=' . $request['priceMax'] . '&';
            }
        }

        if (isset($request['priceMin'])) {
            if ($request['priceMin'] > 0) {
                $url .= 'priceMin=' . $request['priceMin'] . '&';
            }
        }

        if (isset($request['order']) && $request['order'] == 'Giá từ thấp đến cao') {
            $request['order'] = 'desc';
            $url .= 'order=' . $request['order'] . '&';
        } else if (isset($request['order']) && $request['order'] == 'Giá từ cao đến thấp') {
            $request['order'] = 'asc';
            $url .= 'order=' . $request['order'] . '&';
        }

        if (isset($request['price']) && !empty($request['price'] && !isset($request['priceMax'])) && !isset($request['priceMin'])) {
            $priceMin = $request['price'][0];
            $priceMax = $request['price'][1];
            $url .= 'priceMin=' . $priceMin . '&';
            $url .= 'priceMax=' . $priceMax . '&';
        }

        if (isset($request['category']) && !empty($request['category'])) {
            foreach ($request['category'] as $item) {
                $url .= 'category=' . $item . '&';
            }
        }

        if (isset($request['genres']) && !empty($request['genres'])) {
            foreach ($request['genres'] as $item) {
                $url .= 'genres=' . $item . '&';
            }
        }

        if (isset($request['author']) && !empty($request['author'])) {
            $url .= 'author=' . $request['author'][0] . '&';
        }
        
        return rtrim($url, '&');
    }
}
