<template>
  <div class="container">
    <h1 class="text-center">Danh sách bài viết</h1>
    <div class="d-flex row">
      <div
        class="p-2 bd-highlight blog-item col-4"
        v-for="(blog, index) in list"
        :key="index"
      >
        <div class="image">
          <div class="image-blog">
            <div class="image-content">
              <router-link
                :to="{ name: 'DetailBlog', params: { slug: blog.slug } }"
              >
                <img :src="`/storage/${blog.image}`" alt="" />
              </router-link>
            </div>
          </div>
        </div>
        <div class="name-blog">
          <router-link
            :to="{ name: 'DetailBlog', params: { slug: blog.slug } }"
            >{{ blog.title }}</router-link
          >
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "listBlog",
  data() {
    return {
      list: [],
    };
  },
  created() {
    axios.get("/api/bai-viet/danh-sach").then((response) => {
      this.list = response.data;
      console.log(response.data);
    });
  },
};
</script>

<style lang="less" scoped>
.blog-item {
  .image {
    position: relative;
    .image-blog {
      padding-top: 52.925%;
      .image-content {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 100%;
        img {
          height: 190px;
          width: 100%;
          object-fit: cover;
        }
      }
    }
  }
}
</style>