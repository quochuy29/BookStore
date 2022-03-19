<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\blogCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $model = blogCategories::get();
        return response()->json(['data' => $model]);
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
                    Rule::unique('blog_categories')
                ]
            ],
            $message
        );

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'error' => $validator->errors()], 422);
        }
        $model = new blogCategories();
        $model->fill($request->all());

        $model->save();

        return response()->json(['sta' => "đã thành công"]);
    }

    public function updateForm($id)
    {
        return blogCategories::find($id);
    }

    public function update($id, Request $request)
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
                    Rule::unique('blog_categories')->ignore($id)
                ]
            ],
            $message
        );
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'error' => $validator->errors(), 'tag' => $request->all()], 422);
        }
        $model = blogCategories::find($id);

        $model->fill($request->all());

        $model->save();

        return response()->json(['status' => "Cập nhật danh mục bài viết thành công"]);
    }
}
