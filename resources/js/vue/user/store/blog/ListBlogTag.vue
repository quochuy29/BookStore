<template>
  <div class="container">
    <h1 class="text-center">Tag: {{ Tag.name }}</h1>
    <div class="d-flex row">
      <div
        class="p-2 bd-highlight blog-item col-4"
        v-for="(blog, index) in ListBlogTag"
        :key="index"
      >
        <div class="image">
          <div class="image-blog">
            <div class="image-content">
              <img :src="`/storage/${blog.image}`" alt="" />
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
  name: "ListBlogTag",
  props: {
    slug: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      ListBlogTag: [],
      Tag: {},
    };
  },
  mounted() {
    this.loadBlogTag();
    this.loadTag();
  },
  methods: {
    loadBlogTag() {
      axios.get("/api/bai-viet/danh-sach-tag/" + this.slug).then((response) => {
        this.ListBlogTag = response.data;
        console.log(response.data);
      });
    },
    loadTag() {
      axios.get("/api/get-tag/" + this.slug).then((response) => {
        this.Tag = response.data;
      });
    },
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