<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galleries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $gallery = Galleries::with('products');
        if (count($request->all()) > 0) {
            if ($request->search) {
                $gallery = $gallery
                ->join('products', 'products.id', '=','galleries.product_id')
                ->where('products.name', 'like', '%' . $request->search . '%');
            }

            if ($request->product) {
                $gallery = $gallery->where('product_id',$request->product);
            }
            return $gallery->paginate(5);
        }
        return $gallery->paginate(5);
    }

    public function store(Request $request)
    {
        return response()->json(['sta' => $request->file]);
    }

    public function update($id = null, Request $request)
    {
        $message = [
            'name.required' => "Tên danh mục không được trống",
            'name.unique' => "Tên danh mục không được trùng",
            'status.required' => "Hãy chọn trạng thái"
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required',
                    Rule::unique('galleries')->ignore($id)
                ],
                'status' => 'required'
            ],
            $message
        );
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'error' => $validator->errors()], 422);
        }
        $model = Galleries::find($id);

        $model->fill($request->all());

        $model->save();

        return response()->json(['sta' => "update thành công"]);
    }

    public function updateForm($id)
    {
        $model = Galleries::find($id);
        return $model;
    }

    public function delete($id)
    {
        $model = Galleries::find($id);
        $model->delete();
        Storage::delete($model->url_image);
        if ($model) {
            return Galleries::where('product_id', $model->product_id)->get();
        }
        return [''];
    }

    public function upload($id, Request $request)
    {
        // if ($request->file('file')) {

        //     foreach ($request->file as $img) {
        //         $uploadImg = $img->store('images', 'public', uniqid() . '-' . $img->getClientOriginalName());

        //         $galleries = new Galleries();
        //         $galleries->product_id = $id;
        //         $galleries->url_image = $uploadImg;
        //         $galleries->save();
        //     }

        // }

        // return Galleries::where('product_id', $id)->get();
    }
}
