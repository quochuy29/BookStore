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
                'name.required' => "H??y nh???p v??o t??n th?? c??ng",
                'name.unique' => "T??n th?? c??ng ???? t???n t???i",
                'name.min' => "T??n th?? c??ng ??t nh???t 3 k?? t???",
                'slug.required' => "Slug kh??ng ???????c b??? tr???ng",
                'cate_id.required' => "H??y ch???n danh m???c",
                'status.required' => "H??y ch???n tr???ng th??i",
                'price.required' => "H??y nh???p gi?? th?? c??ng",
                'price.numeric' => "Gi?? th?? c??ng ph???i l?? s???",
                'image.required' => 'H??y ch???n ???nh th?? c??ng',
                'image.mimes' => 'File ???nh kh??ng ????ng ?????nh d???ng (jpg, bmp, png, jpeg)',
                'image.max' => 'File ???nh kh??ng ???????c qu?? 2MB',
                'quantity.required' => 'H??y nh???p s??? l?????ng',
                'quantity.numeric' => 'S??? l?????ng ph???i l?? s???'
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

            return response()->json(['sta' => "???? th??nh c??ng"]);
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
                'name.required' => "H??y nh???p v??o t??n th?? c??ng",
                'name.unique' => "T??n th?? c??ng ???? t???n t???i",
                'name.min' => "T??n th?? c??ng ??t nh???t 3 k?? t???",
                'cate_id.required' => "H??y ch???n danh m???c",
                'status.required' => "H??y ch???n tr???ng th??i",
                'price.required' => "H??y nh???p gi?? th?? c??ng",
                'price.numeric' => "Gi?? th?? c??ng ph???i l?? s???",
                'image.mimes' => 'File ???nh kh??ng ????ng ?????nh d???ng (jpg, bmp, png, jpeg)',
                'image.max' => 'File ???nh kh??ng ???????c qu?? 2MB',
                'quantity.required' => 'H??y nh???p s??? l?????ng',
                'quantity.numeric' => 'S??? l?????ng ph???i l?? s???'
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

            return response()->json(['status' => "update th??nh c??ng"]);
        }
    }

    public function delete($id)
    {
        $model = Products::find($id);
        return $model->delete();
    }

    public function detail($id)
    {
        $model = Products::with('categories', 'galleries')->find($id);
        return $model;
    }
}
