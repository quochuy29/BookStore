<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $category = new Categories;

        if (count($request->all()) > 0) {
            if ($request->search) {
                $category = $category->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->isActive > -1) {
                $category = $category->where('status', '=', $request->isActive);
            }

            if ($request->order) {
                switch ($request->order) {
                    case '1':
                        $category = $category->OrderBy('name', 'ASC');
                        break;
                    case '2':
                        $category = $category->OrderBy('name', 'DESC');
                        break;
                }
            }
            return $category->paginate(3);
        }

        return $category->get();
    }

    public function store(Request $request)
    {
        $model = new Categories;
        $message = [
            'name.required' => "Tên danh mục không được trống",
            'name.unique' => "Tên danh mục không được trùng",
            'status.required' => "Hãy chọn trạng thái",
            'slug.required' => "Slug không được trống"
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required',
                    Rule::unique('categories')
                ],
                'status' => 'required',
                'slug' => 'required'
            ],
            $message
        );
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'error' => $validator->errors(), 'tu' => $request->all()], 422);
        }
        $model->fill($request->all());

        $model->save();

        return response()->json(['status' => "đã thành công"]);
    }

    public function update($id = null, Request $request)
    {
        $message = [
            'name.required' => "Tên danh mục không được trống",
            'name.unique' => "Tên danh mục không được trùng",
            'status.required' => "Hãy chọn trạng thái",
            'slug.required' => "Slug không được trống"
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required',
                    Rule::unique('categories')->ignore($id)
                ],
                'status' => 'required',
                'slug' => 'required'
            ],
            $message
        );
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'error' => $validator->errors()], 422);
        }

        $model = Categories::find($id);
        $model->fill($request->all());
        $model->save();

        return response()->json(['status' => "update thành công"]);
    }

    public function updateForm($id)
    {
        $model = Categories::find($id);
        return $model;
    }

    public function delete($id)
    {
        $model = Categories::find($id);
        return $model->delete();
    }
}
