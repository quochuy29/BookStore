<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Service\RequestService;
use App\Models\AuthorProduct;
use App\Models\GenresProduct;
use App\Models\Order_detail;
use App\Models\Products;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                $product = $product->where('products.name', 'like', '%' . $request->search . '%');
            }

            if ($request->category) {
                $product = $product->whereIn('products.cate_id', $request->category);
            }

            if ($request->genres) {
                $genres = GenresProduct::whereIn('genres_id', $request->genres)->pluck('product_id');
                $product = $product->whereIn('products.id', $genres);
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
                        $product = $product->whereIn('products.id', $productIdMore);
                        break;
                    case '2':
                        foreach (array_unique($author) as $item) {
                            if (!empty($products[$item])) {
                                if ($products[$item] == 1) {
                                    array_push($productIdMore, $item);
                                }
                            }
                        }
                        $product = $product->whereIn('products.id', $productIdMore);
                        break;
                }
            }


            if ($min >= 0) {
                if ($max == 0) {
                    $product = $product->where('products.price', '>=', $min);
                } else {
                    $product = $product->where('products.price', '<=', $max)
                        ->where('products.price', '>=', $min);
                }
            }

            if ($request->sortBy) {
                switch ($request->sortBy) {
                    case 'new':
                        $product = $product->where('products.created_at', 'like', '%' . Carbon::now()->format('Y-m') . '%');
                        break;
                    case 'price':
                        if ($request->order == 'Gi?? t??? th???p ?????n cao') {
                            $product = $product->orderBy('products.price', 'desc');
                        } else {
                            $product = $product->orderBy('products.price', 'asc');
                        }
                        break;
                    case 'selling':
                        $product = $product->join('order_detail', 'order_detail.product_id', '=', 'products.id')
                            ->select(DB::raw('sum(order_detail.quantity) as count,products.*'))
                            ->groupBy('products.id')->orderBy('count', 'desc');
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
        $order = Order_detail::select(DB::raw('sum(order_detail.quantity) as count'))
            ->join('products', 'products.id', '=', 'order_detail.product_id')
            ->where('products.slug', 'like', $request->slug)
            ->groupBy('products.id')->pluck('count');
        $product = Products::with('categories', 'galleries', 'genresProducts', 'authorProducts')->where('slug', 'like', $request->slug)->first();

        return response()->json(['data' => $product, 'count' => $order]);
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

    public function showSearch(Request $request)
    {
        $product = [];
        if ($request->search != '') {
            $product = Products::where('name', 'like', '%' . $request->search . '%');
            return $product->limit(6)->get();
        }

        return $product;
    }
}
