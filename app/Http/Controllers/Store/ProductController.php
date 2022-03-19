<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Service\RequestService;
use App\Models\AuthorProduct;
use App\Models\GenresProduct;
use App\Models\Products;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public $requestService;
    public function __construct(RequestService $requestService)
    {
        $this->requestService = $requestService;
    }

    public function index(Request $request)
    {
        $createRequest = $this->requestService->createRequest($request->all());
        $min = $request->priceMin ? $request->priceMin : 0;
        $max = $request->priceMax ? $request->priceMax : 0;
        $product = new Products();
        if (count($request->all()) > 0) {
            if ($request->search) {
                $product = $product->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->category) {
                $product = $product->whereIn('cate_id', $request->category);
            }

            if ($request->genres) {
                $genres = GenresProduct::whereIn('genres_id', $request->genres)->pluck('product_id');
                $product = $product->whereIn('id', $genres);
            }
            if ($request->author) {
                $idPro = [];
                $author = [];
                $productIdMore = [];
                foreach (Products::get() as $item) {
                    array_push($idPro, $item->id);
                }
                $authors = AuthorProduct::whereIn('product_id', $idPro)->pluck('product_id');
                foreach ($authors as $item) {
                    array_push($author, $item);
                }
                $products = array_count_values($author);
                switch ($request->author[0]) {
                    case '1':
                        foreach (array_unique($author) as $item) {
                            if (!empty($products[$item])) {
                                if ($products[$item] > 1) {
                                    array_push($productIdMore, $item);
                                }
                            }
                        }
                        $product = $product->whereIn('id', $productIdMore);
                        break;
                    case '2':
                        foreach (array_unique($author) as $item) {
                            if (!empty($products[$item])) {
                                if ($products[$item] == 1) {
                                    array_push($productIdMore, $item);
                                }
                            }
                        }
                        $product = $product->whereIn('id', $productIdMore);
                        break;
                }
            }


            if ($min >= 0) {
                if ($max == 0) {
                    $product = $product->where('price', '>=', $min);
                } else {
                    $product = $product->where('price', '<=', $max)
                        ->where('price', '>=', $min);
                }
            }

            if ($request->sortBy) {
                switch ($request->sortBy) {
                    case 'new':
                        $product = $product->where('created_at', 'like', '%' . Carbon::now()->format('Y-m') . '%');
                        break;
                    case 'price':
                        if ($request->order == 'Giá từ thấp đến cao') {
                            $product = $product->orderBy('price', 'desc');
                        } else {
                            $product = $product->orderBy('price', 'asc');
                        }
                        break;
                }
            }

            return response()->json(['data' => $product->paginate(12), 'url' => $createRequest]);
        }
        return response()->json(['data' => $product->paginate(12), 'url' => $createRequest]);
    }

    public function featured()
    {
        return Products::orderBy('created_at', 'desc')->limit(8)->get();
    }

    public function detail(Request $request)
    {
        return Products::with('categories', 'galleries', 'genresProducts', 'authorProducts')->where('slug', 'like', $request->slug)->first();
    }

    public function relateProduct($id)
    {
        if ($id > 0) {
            $product = Products::find($id);
            $related = Products::with('categories')
                ->where('cate_id', $product->cate_id)
                ->paginate(6);
        } else {
            $related = [];
        }
        return $related;
    }

    public function relateProductDetail($id)
    {
        $product = Products::find($id);
        $related = Products::with('categories')
            ->where('cate_id', $product->cate_id)
            ->paginate(15);

        return $related;
    }
}
