<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GenresController extends Controller
{
    public function index(Request $request)
    {
        $genres = new Genres();

        if (count($request->all()) > 0) {
            if ($request->search) {
                $genres = $genres->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->isActive > -1) {
                $genres = $genres->where('status', '=', $request->isActive);
            }

            if ($request->order) {
                switch ($request->order) {
                    case '1':
                        $genres = $genres->OrderBy('name', 'ASC');
                        break;
                    case '2':
                        $genres = $genres->OrderBy('name', 'DESC');
                        break;
                }
            }

            return $genres->paginate(3);
        }
        return $genres->get();
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
            return response()->json(['status' => 422, 'error' => $validator->errors(), 'tu' => $request->all()], 422);
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
                    Rule::unique('genres')->ignore($id)
                ],
                'status' => 'required'
            ],
            $message
        );
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'error' => $validator->errors(), 'tu' => $request->all()], 422);
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
