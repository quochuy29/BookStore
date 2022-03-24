<?php

use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\GenresController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Permissions;
use App\Http\Controllers\Store\AuthorController as StoreAuthorController;
use App\Http\Controllers\Store\BlogCategoryController as StoreBlogCategoryController;
use App\Http\Controllers\Store\BlogController as StoreBlogController;
use App\Http\Controllers\Store\CartController;
use App\Http\Controllers\Store\CategoryController as StoreCategoryController;
use App\Http\Controllers\Store\GenresController as StoreGenresController;
use App\Http\Controllers\Store\ProductController as StoreProductController;
use App\Http\Controllers\Store\TagBlogController;
use App\Http\Controllers\Store\TagController as StoreTagController;
use App\Http\Controllers\Store\WishListController;
use App\Http\Controllers\UploadImageTiny;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('/categories')
    ->controller(CategoryController::class)
    ->group(function () {
        Route::get('', 'index');
        Route::get('/updateForm/{id}', 'updateForm');
        Route::post('/store', 'store');
        Route::post('/update/{id}', 'update');
        Route::delete('/delete/{id}', 'delete');
    });

Route::prefix('/products')
    ->controller(ProductController::class)
    ->group(function () {
        Route::get('', 'index');
        Route::get('/detail/{id}', 'detail');
        Route::get('/updateForm/{id}', 'updateForm');
        Route::post('/store', 'store');
        Route::post('/update/{id}', 'update');
        Route::delete('/delete/{id}', 'delete');
    });

Route::prefix('/genres')
    ->controller(GenresController::class)
    ->group(function () {
        Route::get('', 'index');
        Route::get('/updateForm/{id}', 'updateForm');
        Route::post('/store', 'store');
        Route::post('/update/{id}', 'update');
        Route::delete('/delete/{id}', 'delete');
    });

Route::prefix('/author')
    ->controller(AuthorController::class)
    ->group(function () {
        Route::get('', 'index');
        Route::get('/updateForm/{id}', 'updateForm');
        Route::post('/store', 'store');
        Route::post('/update/{id}', 'update');
        Route::delete('/delete/{id}', 'delete');
    });

Route::post('/upload', [UploadImageTiny::class, 'upload']);

Route::prefix('/gallery')
    ->controller(GalleryController::class)
    ->group(function () {
        Route::get('', 'index');
        Route::get('/updateForm/{id}', 'updateForm');
        Route::post('/store', 'store');
        Route::post('/update/{id}', 'update');
        Route::post('/upload/{id}', 'upload');
        Route::delete('/delete/{id}', 'delete');
    });

Route::prefix('/blogs')
    ->controller(BlogController::class)
    ->group(function () {
        Route::get('', 'index');
        Route::post('/store', 'store')->middleware('auth:sanctum');
        Route::get('/updateForm/{id}', 'updateForm');
        Route::post('/update/{id}', 'update')->middleware('auth:sanctum');
        Route::get('/detail/{id}', 'detail');
        Route::delete('/delete/{id}', 'delete');
    });

Route::prefix('/blog-categories')
    ->controller(BlogCategoryController::class)
    ->group(function () {
        Route::get('', 'index');
        Route::get('/updateForm/{id}', 'updateForm');
        Route::post('/store', 'store');
        Route::post('/update/{id}', 'update');
        Route::delete('/delete/{id}', 'delete');
    });

Route::prefix('/tag')
    ->controller(TagController::class)
    ->group(function () {
        Route::get('', 'index');
        Route::get('/updateForm/{id}', 'updateForm');
        Route::post('/store', 'store');
        Route::post('/update/{id}', 'update');
        Route::delete('/delete/{id}', 'delete');
    });

Route::prefix('/auth')
    ->controller(AuthController::class)
    ->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/logout', 'logout')->middleware('auth:sanctum');
        Route::get('/checkRole', 'checkRole')->middleware('permission:admin', 'auth:sanctum');
    });

Route::prefix('/product')
    ->controller(StoreProductController::class)
    ->group(function () {
        Route::get('', 'index');
        Route::get('/featured', 'featured');
        Route::get('/showSearch', 'showSearch');
        Route::get('/detail/{slug}', 'detail');
        Route::get('/related/{id}', 'relateProduct');
        Route::get('/related-product-detail/{id}', 'relateProductDetail');
    });

Route::prefix('danh-muc')
    ->controller(StoreCategoryController::class)
    ->group(function () {
        Route::get('', 'index')->middleware('auth:sanctum');
    });

Route::prefix('the-loai-sach')
    ->controller(StoreGenresController::class)
    ->group(function () {
        Route::get('danh-sach', 'listGenres');
        Route::get('/{id}', 'index');
    });

Route::prefix('tac-gia-sach')
    ->controller(StoreAuthorController::class)
    ->group(function () {
        Route::get('/{id}', 'index');
    });

Route::prefix('gio-hang')
    ->controller(CartController::class)
    ->group(function () {
        Route::get('', 'index');
        Route::get('/cart', 'cart');
        Route::post('/add-to-cart/{id}', 'addCart');
        Route::get('/edit-to-cart/{id}', 'editCart');
        Route::delete('/delete-to-cart/{id}', 'deleteCart');
        Route::delete('/clear-to-cart', 'clearCart');
        Route::post('/clear-check-cart', 'clearCheck');
        Route::get('/check-out', 'order');
        Route::post('/pay-ment', 'payment');
        Route::get('return', 'returnUrl')->name('returnUrl');
    });

Route::prefix('wish-list')
    ->controller(WishListController::class)
    ->group(function () {
        Route::get('', 'index')->middleware('auth:sanctum');
        Route::get('/product-available', 'productAvailable')->middleware('auth:sanctum');
        Route::post('/add', 'addWishList')->middleware('auth:sanctum');
        Route::delete('/delete/{id}', 'deleteWish')->middleware('auth:sanctum');
    });

Route::prefix('bai-viet')
    ->controller(StoreBlogController::class)
    ->group(function () {
        Route::get('danh-sach', 'index');
        Route::get('moi-nhat', 'newBlog');
        Route::get('/{slug}', 'detail');
        Route::get('tag/{id}', 'tag');
        Route::get('lien-quan/{id}', 'related');
        Route::get('danh-sach-tag/{slug}', 'blogTag');
    });

Route::prefix('get-tag')
    ->controller(StoreTagController::class)
    ->group(function () {
        Route::get('', 'index');
        Route::get('/{slug}', 'tag');
    });

Route::prefix('danh-muc-bai-viet')
    ->controller(StoreBlogCategoryController::class)
    ->group(function () {
        Route::get('', 'index');
    });

Route::prefix('phan-quyen')
    ->controller(Permissions::class)
    ->group(function () {
        Route::get('', 'index')->middleware('auth:sanctum');;
    });
