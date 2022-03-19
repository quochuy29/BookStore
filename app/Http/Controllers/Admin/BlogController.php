<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Service\TagBlogService;
use App\Models\blogs;
use App\Models\tagBlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BlogController extends Controller
{
    public $TagBlogService;

    public function __construct(TagBlogService $TagBlogService)
    {
        $this->TagBlogService = $TagBlogService;
    }

    public function index()
    {
        $data = blogs::with('blog_cate')->get();
        return response()->json(['data'=>$data]);
    }

    public function store($id = null, Request $request)
    {
        $message = [
            'title.required' => "Hãy nhập vào tiêu đề bài viết",
            'title.unique' => "Tiêu đề bài viết đã tồn tại",
            'title.min' => "Tiêu đề bài viết ít nhất 3 kí tự",
            'blogCate_id.required' => "Hãy chọn danh mục",
            'image.required' => 'Hãy chọn ảnh bài viết',
            'image.mimes' => 'File ảnh không đúng định dạng (jpg, bmp, png, jpeg)',
            'image.max' => 'File ảnh không được quá 2MB',
            'description.required' => 'Nội dung không được bỏ trống'
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'title' => [
                    'required',
                    'min:3',
                    Rule::unique('blogs')->ignore($id),
                ],
                'blogCate_id' => 'required',
                'description' => 'required',
                'image' => 'required|mimes:jpg,bmp,png,jpeg|max:2048'
            ],
            $message
        );

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'error' => $validator->errors(),'tu'=>$request->all()], 422);
        }

        $model = new blogs();
        $model->fill($request->all());
        $model->user_id = Auth::id();
        if ($request->has('image')) {
            $model->image = $request->file('image')->storeAs(
                'uploads/products/',
                '-' . $request->image->getClientOriginalName()
            );
        }
        $model->save();

        if ($request->tag) {
            $tag = array_map('intval', explode(',', $request->tag));
            $this->TagBlogService->createTagBlog($tag, $model->id);
        }

        return response()->json(['sta' => "đã thành công"]);
    }

    public function updateForm($id)
    {
        return blogs::with('tag_blog')->find($id);
    }

    public function update($id, Request $request)
    {
        $message = [
            'title.required' => "Hãy nhập vào tiêu đề bài viết",
            'title.unique' => "Tiêu đề bài viết đã tồn tại",
            'title.min' => "Tiêu đề bài viết ít nhất 3 kí tự",
            'blogCate_id.required' => "Hãy chọn danh mục",
            'image.mimes' => 'File ảnh không đúng định dạng (jpg, bmp, png, jpeg)',
            'image.max' => 'File ảnh không được quá 2MB',
            'description.required' => 'Nội dung không được bỏ trống'
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'title' => [
                    'required',
                    'min:3',
                    Rule::unique('blogs')->ignore($id),
                ],
                'blogCate_id' => 'required',
                'description' => 'required',
                'image' => 'mimes:jpg,bmp,png,jpeg|max:2048'
            ],
            $message
        );

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'error' => $validator->errors()], 422);
        }

        $model = blogs::find($id);
        $model->fill($request->all());
        $model->user_id = Auth::id();
        if ($request->has('image')) {
            $model->image = $request->file('image')->storeAs(
                'uploads/products/',
                '-' . $request->image->getClientOriginalName()
            );
        }
        $model->save();

        if ($request->tag) {
            $tagBlog = tagBlog::where('blog_id', $id)->pluck('tag_id')->toArray();
            $tag = array_map('intval', explode(',', $request->tag));

            $this->TagBlogService->EditTagBlog($tag,$tagBlog,$id);
        }

        return response()->json(['sta' => "đã thành công"]);
    }
}
