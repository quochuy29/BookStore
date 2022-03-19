<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\WishList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishListController extends Controller
{
    public function index()
    {
        $model = WishList::with('products', 'category')->where('user_id', Auth::id());
        return $model->get();
    }

    public function addWishList(Request $request)
    {
        $model = new WishList();
        $mol = $model->where([
            'product_id' => $request->product_id,
            'user_id' => Auth::id()
        ])->get();
        if (count($mol) > 0) {
            return response()->json(['status' => 'Sản phẩm này đã có trong danh sách sản phẩm yêu thích']);
        }
        $model->fill($request->all());
        $model->user_id = Auth::id();
        $model->save();
        return response()->json(['status' => 'Thêm vào sản phẩm yêu thích thành công']);
    }

    public function productAvailable()
    {
        $model = WishList::where('user_id',Auth::id())->get();
        $id = [];
        foreach($model as $key => $item){
            array_push($id,$item->product_id);
            $product = Products::whereNotIn('id',$id)->get();
        }

        return $product;
    }

    public function deleteWish($id)
    {
        WishList::where('id',$id)->delete();
        return response()->json(['status' => 'Xoá sản phẩm yêu thích thành công']);
    }
}
