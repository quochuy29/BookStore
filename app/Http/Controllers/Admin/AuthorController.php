<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Authors;
use App\Models\Genres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthorController extends Controller
{

    public function index(Request $request)
    {
        $author = new Authors();
        if (count($request->all()) > 0) {
            if ($request->search) {
                $author = $author->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->order) {
                switch ($request->order) {
                    case '1':
                        $author = $author->OrderBy('name', 'ASC');
                        break;
                    case '2':
                        $author = $author->OrderBy('name', 'DESC');
                        break;
                }
            }

            return $author->paginate(3);
        }
        return $author->get();
    }

    public function store(Request $request)
    {
        $model = new Genres();
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
                    Rule::unique('genres')
                ],
                'status' => 'required'
            ],
            $message
        );
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'error' => $validator->errors()], 422);
        }
        $model->fill($request->all());

        $model->save();

        return response()->json(['sta' => "đã thành công"]);
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
                    Rule::unique('categories')->ignore($id)
                ],
                'statsu' => 'required'
            ],
            $message
        );
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'error' => $validator->errors()], 422);
        }
        $model = Genres::find($id);

        $model->fill($request->all());

        $model->save();

        return response()->json(['sta' => "update thành công"]);
    }

    public function updateForm($id)
    {
        $model = Genres::find($id);
        return $model;
    }

    public function delete($id)
    {
        $model = Genres::find($id);
        return $model->delete();
    }
}
