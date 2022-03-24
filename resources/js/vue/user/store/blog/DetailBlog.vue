<template>
  <div class="container">
    <div class="product-exist" v-if="detail == ''">
      <img src="/storage/image/a60759ad1dabe909c46a817ecbf71878.png" alt="" />
      <p>Oops , Bài viết này không tồn tại !</p>
    </div>
    <div class="row" v-if="detail != ''">
      <div class="content col-9">
        <div class="title">
          {{ detail.title }}
        </div>
        <div class="datetime">
          Ngày đăng : {{ detail.updated_at | moment("h:mm L") }}
        </div>
        <div class="blog-categories d-flex">
          Tag :
          <div
            class="categories badge badge-primary ml-1"
            v-for="(tags, index) in tag"
            :key="index"
          >
            <router-link
              class="text"
              :to="{ name: 'ListBlogTag', params: { slug: tags.slug } }"
              >{{ tags.name }}</router-link
            >
          </div>
        </div>
        <div v-html="detail.description" class="content-title"></div>
        <div v-if="detail.user" class="author text-right">
          Tác giả : {{ detail.user.name }}
        </div>
      </div>
      <div class="sidebar-content col-3">
        <div
          class="blog-related"
          v-for="(relate, index) in blogRelated"
          :key="index"
        >
          <div class="title-relate">
            <router-link
              :to="{ name: 'DetailBlog', params: { slug: relate.slug } }"
              >{{ relate.title }}</router-link
            >
          </div>
          <div class="img-relate">
            <img :src="`/storage/${relate.image}`" alt="" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "DetailBlog",
  props: {
    slug: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      detail: {},
      tag: [],
      blogRelated: [],
    };
  },
  mounted() {
    this.loadDetailBlog();
  },
  methods: {
    async loadDetailBlog() {
      await axios.get("/api/bai-viet/" + this.slug).then((response) => {
        this.detail = response.data;
        this.loadTagBlog(this.detail.id);
        this.loadBlog(this.detail.id);
      });
    },
    async loadBlog(id) {
      await axios.get("/api/bai-viet/lien-quan/" + id).then((response) => {
        this.blogRelated = response.data;
      });
    },
    async loadTagBlog(id) {
      await axios.get("/api/bai-viet/tag/" + id).then((response) => {
        this.tag = response.data.tag;
      });
    },
  },
};
</script>

<style lang="less" scoped>
.img-relate {
  width: 100%;
  img {
    object-fit: cover;
    width: 100%;
  }
}

.text {
  color: #fff;
}

.product-exist{
  text-align: center;
  img{
    width: 115px;
  }
}
</style>