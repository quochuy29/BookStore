<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\tags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    public function index()
    {
        $tag = tags::get();
        return response()->json(['data' => $tag]);
    }

    public function store($id = null, Request $request)
    {
        $message = [
            'name.required' => "Tên danh mục không được trống",
            'name.unique' => "Tên danh mục không được trùng"
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required',
                    Rule::unique('tags')
                ]
            ],
            $message
        );

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'error' => $validator->errors()], 422);
        }
        $model = new tags();
        $model->fill($request->all());

        $model->save();

        return response()->json(['sta' => "đã thành công"]);
    }

    public function updateForm($id)
    {
        $model = tags::find($id);
        return $model;
    }

    public function update($id = null, Request $request)
    {
        $message = [
            'name.required' => "Tên danh mục không được trống",
            'name.unique' => "Tên danh mục không được trùng",
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required',
                    Rule::unique('tags')->ignore($id)
                ]
            ],
            $message
        );
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'error' => $validator->errors(), 'tag' => $request->all()], 422);
        }
        $model = tags::find($id);

        $model->fill($request->all());

        $model->save();

        return response()->json(['status' => "Cập nhật tag thành công"]);
    }

    public function delete($id)
    {
        $model = tags::find($id);
        if($model){
            $model->delete();
            return response()->json(['status'=>'Xoá tag thành công']);
        }
        return response()->json(['status'=>'Tag không tồn tại . Xoá thất bại']);
    }
}
