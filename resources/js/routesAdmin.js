import Error from './vue/error'
import ListCategory from './vue/admin/category/ListCategory'
import ListProduct from './vue/admin/product/ListProduct'
import ListGenres from './vue/admin/genres/ListGenres'
import DetailProduct from './vue/admin/product/DetailProduct'
import ListGallery from './vue/admin/galleries/ListGallery'
import Login from './vue/auth/login'
import Register from './vue/auth/register'
import Profile from './vue/auth/profile'
import Main from './vue/admin/layouts/main'
import HomePage from './vue/user/store/HomePage'
import MainStore from './vue/user/layouts/main'
import BookDetail from './vue/user/store/products/BookDetail'
import Cart from './vue/user/store/cart/Cart'
import CheckOut from './vue/user/store/cart/CheckOut'
import WishList from './vue/user/store/products/WishList';
import ListBlog from './vue/admin/blogs/ListBlog'
import ListTag from './vue/admin/tags/ListTag'
import ListCategories from './vue/admin/blogcategories/ListCategories'
import ListBlogUser from './vue/user/store/blog/ListBlog'
import DetailBlog from './vue/user/store/blog/DetailBlog'
import ListBlogTag from './vue/user/store/blog/ListBlogTag'
import Products from './vue/user/store/products/ListProduct'
import DetailBlogs from './vue/admin/blogs/DetailBlog'
import Page404 from './vue/errors/404'
export default {
    mode: 'history',
    base: process.env.BASE_URL,
    fallback: true,
    routes: [{
            path: '',
            component: MainStore,
            meta: { requiresAuth: false },
            children: [{
                    path: 'home',
                    alias: '/',
                    name: 'homepage',
                    component: HomePage,
                    meta: { requiresAuth: false }
                },
                {
                    path: 'san-pham',
                    name: 'books',
                    component: Products,
                    meta: { requiresAuth: false }
                },
                {
                    path: 'san-pham/:name',
                    name: 'detailsBook',
                    component: BookDetail,
                    props: true,
                    meta: { requiresAuth: false }
                },
                {
                    path: 'gio-hang',
                    name: 'cart',
                    component: Cart,
                    props: true,
                    meta: { requiresAuth: false }
                },
                {
                    path: 'gio-hang/thanh-toan',
                    name: 'checkOut',
                    component: CheckOut,
                    meta: { requiresAuth: false }
                },
                {
                    path: 'san-pham-yeu-thich',
                    name: 'wishList',
                    component: WishList,
                    meta: { requiresAuth: true }
                },
                {
                    path: 'bai-viet/danh-sach',
                    name: 'listBlogUser',
                    component: ListBlogUser,
                    meta: { requiresAuth: false }
                },
                {
                    path: 'bai-viet/danh-sach/:slug',
                    name: 'DetailBlog',
                    component: DetailBlog,
                    props: true,
                    meta: { requiresAuth: false }
                },
                {
                    path: 'bai-viet/tag/:slug',
                    name: 'ListBlogTag',
                    component: ListBlogTag,
                    props: true,
                    meta: { requiresAuth: false }
                },
            ]
        },
        {
            path: '/logout',
            meta: { requiresAuth: false }
        },
        {
            path: '/login',
            component: Login,
            meta: { requiresAuth: false }
        },
        {
            path: '/register',
            component: Register,
            meta: { requiresAuth: false }
        },
        {
            path: '/admin',
            component: Main,
            meta: { guest: true },
            children: [{
                    path: 'listCategory',
                    component: ListCategory,
                    meta: { requiresAuth: true }
                },
                {
                    path: 'listProduct',
                    alias: '/admin',
                    component: ListProduct,
                    name: 'product',
                    meta: { requiresAuth: true }
                },
                {
                    path: 'listBlog',
                    component: ListBlog,
                    name: 'blog',
                    meta: { requiresAuth: true }
                },
                {
                    path: 'listBlogCategories',
                    component: ListCategories,
                    name: 'categoryBlog',
                    meta: { requiresAuth: true }
                },
                {
                    path: 'listTag',
                    component: ListTag,
                    name: 'tag',
                    meta: { requiresAuth: true }
                },
                {
                    path: 'listGenres',
                    component: ListGenres,
                    meta: { requiresAuth: true }
                },
                {
                    path: 'products/:id',
                    component: DetailProduct,
                    name: 'details',
                    props: true,
                    meta: { requiresAuth: true }
                },
                {
                    path: 'blogs/:id',
                    component: DetailBlogs,
                    name: 'detailBlogs',
                    props: true,
                    meta: { requiresAuth: true }
                },
                {
                    path: 'gallery-product',
                    component: ListGallery,
                    meta: { requiresAuth: true }
                },
                {
                    path: '/profile',
                    component: Profile,
                    meta: { requiresAuth: true }
                },
            ],
        },
        {
            path: '/:NotFound(.*)*',
            component: Page404,
        },
    ]
}