<template>
  <div>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="" class="brand-link"> </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image"></div>
        </div>
        <nav class="mt-2">
          <ul
            class="nav nav-pills nav-sidebar flex-column"
            data-widget="treeview"
            role="menu"
            data-accordion="false"
          >
            <li class="nav-item">
              <a href="" class="nav-link border border-secondary rounded">
                <i class="fa fa-home"></i>
                <p>Trang chủ</p>
              </a>
            </li>
            <li class="nav-header">Sản phẩm</li>
            <li class="nav-item border-bottom border-secondary">
              <a href="#" class="nav-link">
                <i class="fas fa-project-diagram"></i>
                <p>
                  Sản phẩm & Danh mục
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link
                    to="/admin/listProduct"
                    class="nav-item nav-link"
                  >
                    <i class="far fa-circle nav-icon"></i>
                    <p>Danh sách sản phẩm</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link
                    to="/admin/listCategory"
                    class="nav-item nav-link"
                  >
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                      Danh sách danh mục
                    </p>
                  </router-link>
                </li>
              </ul>
            </li>
            <li class="nav-header">Thể loại sách</li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-paw"></i>
                <p>
                  Quản lý thể loại sách
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="/admin/listGenres" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Danh sách thể loại</p>
                  </router-link>
                </li>
            
              </ul>
            </li>
            <li class="nav-item border-bottom border-secondary">
              <a href="#" class="nav-link">
                <i class="fas fa-cat"></i>
                <p>
                  Quản lý thư viện hình ảnh
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="/admin/gallery-product" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Danh sách thư viện</p>
                  </router-link>
                </li>
              </ul>
            </li>
            <li class="nav-header">Bài viết</li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-swatchbook"></i>
                <p>
                 Bài viết
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link :to="{name:'blog'}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Danh sách bài viết</p>
                  </router-link>
                </li>
              </ul>
            </li>
            <li class="nav-header">Tag</li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-swatchbook"></i>
                <p>
                 Tag
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link :to="{name:'tag'}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Danh sách Tag</p>
                  </router-link>
                </li>
              </ul>
            </li>
            <li class="nav-header">Danh mục bài viết</li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-swatchbook"></i>
                <p>
                 Danh mục bài viết
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link :to="{name:'categoryBlog'}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Danh sách danh mục</p>
                  </router-link>
                </li>
              </ul>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>
  </div>
</template>

<script>
export default {
  name: "SideBar",
  data() {
    return {};
  },
};
</script>

<style lang="css" scoped>
</style>