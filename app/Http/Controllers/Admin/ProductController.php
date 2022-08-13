<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Service\AuthorProductsService;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Service\GalleryService;
use App\Http\Service\GenresProductsService;
use App\Models\AuthorProduct;
use App\Models\GenresProduct;

class ProductController extends Controller
{
    public $GenresProduct;
    public $GalleryService;
    public $AuthorProduct;

    public function __construct(GenresProductsService $GenresProduct, GalleryService $GalleryService, AuthorProductsService $AuthorProduct)
    {
        $this->GenresProduct = $GenresProduct;
        $this->GalleryService = $GalleryService;
        $this->AuthorProduct = $AuthorProduct;
    }

    public function index(Request $request)
    {
        $product = Products::with('categories');

        if (count($request->all()) > 0) {
            if ($request->search) {
                $product = $product->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->category) {
                $product = $product->where('cate_id', $request->category);
            }

            if ($request->order) {
                switch ($request->order) {
                    case '1':
                        $product = $product->orderBy('name', 'ASC');
                        break;
                    case '2':
                        $product = $product->orderBy('name', 'DESC');
                        break;
                    case '3':
                        $product = $product->orderBy('price', 'ASC');
                        break;
                    case '4':
                        $product = $product->orderBy('price', 'DESC');
                        break;
                }
            }
            return $product->paginate(7);
        }

        return $product->get();
    }

    public function store($id = null, Request $request, $product_id = 0)
    {
        if (count($request->all()) >= 5) {

            $message = [
                'name.required' => "Hãy nhập vào tên thú cưng",
                'name.unique' => "Tên thú cưng đã tồn tại",
                'name.min' => "Tên thú cưng ít nhất 3 kí tự",
                'slug.required' => "Slug không được bỏ trống",
                'cate_id.required' => "Hãy chọn danh mục",
                'status.required' => "Hãy chọn trạng thái",
                'price.required' => "Hãy nhập giá thú cưng",
                'price.numeric' => "Giá thú cưng phải là số",
                'image.required' => 'Hãy chọn ảnh thú cưng',
                'image.mimes' => 'File ảnh không đúng định dạng (jpg, bmp, png, jpeg)',
                'image.max' => 'File ảnh không được quá 2MB',
                'quantity.required' => 'Hãy nhập số lượng',
                'quantity.numeric' => 'Số lượng phải là số'
            ];
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => [
                        'required',
                        'min:3',
                        Rule::unique('products')->ignore($id),
                    ],
                    'cate_id' => 'required',
                    'status' => 'required',
                    'price' => 'required|numeric',
                    'quantity' => 'required|numeric',
                    'image' => 'required|mimes:jpg,bmp,png,jpeg|max:2048',
                    'slug' => 'required'
                ],
                $message
            );

            if ($validator->fails()) {
                return response()->json(['status' => 422, 'error' => $validator->errors(), 'tu' => $request->all()], 422);
            }

            $model = new Products;
            $model->fill($request->all());
            if ($request->has('image')) {
                $model->image = $request->file('image')->storeAs(
                    'uploads/products/',
                    '-' . $request->image->getClientOriginalName()
                );
            }
            $model->save();

            if ($request->file('file')) {
                $this->GalleryService->createGallery($request->file, $request->RemoveImg, $model->id);
            }

            if ($request->genres) {
                $gen = array_map('intval', explode(',', $request->genres));
                $this->GenresProduct->createGenresProduct($gen, $model->id);
            }

            if ($request->author) {
                $author = array_map('intval', explode(',', $request->author));
                $this->AuthorProduct->createAuthor($author, $model->id);
            }

            return response()->json(['sta' => "đã thành công"]);
        }
    }

    public function updateForm($id)
    {
        $model = Products::with('galleries', 'genresProducts','authorProducts')->find($id);
        return $model;
    }

    public function update($id = null, Request $request)
    {

        if ($request->file('file')) {
            $this->GalleryService->createGallery($request->file, $request->RemoveImg, $id);
        }

        if (count($request->all()) >= 5) {
            $genresProduct = GenresProduct::where('product_id', $id)->pluck('genres_id')->toArray();
            $gen = array_map('intval', explode(',', $request->genres));

            $authorProduct = AuthorProduct::where('product_id', $id)->pluck('author_id')->toArray();
            $author = array_map('intval', explode(',', $request->author));

            $message = [
                'name.required' => "Hãy nhập vào tên thú cưng",
                'name.unique' => "Tên thú cưng đã tồn tại",
                'name.min' => "Tên thú cưng ít nhất 3 kí tự",
                'cate_id.required' => "Hãy chọn danh mục",
                'status.required' => "Hãy chọn trạng thái",
                'price.required' => "Hãy nhập giá thú cưng",
                'price.numeric' => "Giá thú cưng phải là số",
                'image.mimes' => 'File ảnh không đúng định dạng (jpg, bmp, png, jpeg)',
                'image.max' => 'File ảnh không được quá 2MB',
                'quantity.required' => 'Hãy nhập số lượng',
                'quantity.numeric' => 'Số lượng phải là số'
            ];
            
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => [
                        'required',
                        'min:3',
                        Rule::unique('products')->ignore($id),
                    ],
                    'cate_id' => 'required',
                    'status' => 'required',
                    'price' => 'required|numeric',
                    'quantity' => 'required|numeric',
                    'image' => 'mimes:jpg,bmp,png,jpeg|max:2048'
                ],
                $message
            );

            if ($validator->fails()) {
                return response()->json(['status' => 422, 'error' => $validator->errors()], 422);
            }
            $model = Products::find($id);
            $model->fill($request->all());
            if ($request->has('image')) {
                $model->image = $request->file('image')->storeAs(
                    'uploads/categories/',
                    '-' . $request->image->getClientOriginalName()
                );
            }
            $model->save();

            $this->GenresProduct->EditGenresProduct($gen, $genresProduct, $id);

            $this->AuthorProduct->EditAuthor($author, $authorProduct, $id);

            return response()->json(['status' => "update thành công"]);
        }
    }

    public function delete($id)
    {
        $model = Products::find($id);
        $model->galleries->delete();
        $model->genresProducts->delete();
        $model->authorProducts->delete();
        $model->orderDetail->delete();
        $model->delete();
        return response()->json(['status'=> 'Xoá sản phẩm thành công']);
    }

    public function detail($id)
    {
        $model = Products::with('categories', 'galleries')->find($id);
        return $model;
    }
}
