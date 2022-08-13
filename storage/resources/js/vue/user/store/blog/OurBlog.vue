<template>
  <section class="ourblog" id="ourblog">
    <h1 class="heading"><span>Our books</span></h1>
    <hooper
      :progress="true"
      :infiniteScroll="true"
      :itemsToShow="3"
      pagination="fraction"
    >
      <slide v-for="(blogs, index) in blog" :key="index">
        <div class="item">
          <div class="book-image">
            <img
              :src="`/storage/${blogs.image}`"
              :alt="blogs.name"
              :title="blogs.name"
            />
          </div>
          <div class="book-title">
            <router-link
              :to="{ name: 'DetailBlog', params: { slug: blogs.slug } }"
              >{{ blogs.title }}</router-link
            >
          </div>
        </div>
      </slide>
      <hooper-pagination slot="hooper-addons"></hooper-pagination>
      <hooper-progress slot="hooper-addons"></hooper-progress>
    </hooper>
  </section>
</template>

<script>
import {
  Hooper,
  Slide,
  Pagination as HooperPagination,
  Progress as HooperProgress,
} from "hooper";
import "hooper/dist/hooper.css";
export default {
  name: "OurBlog",
  data() {
    return {
      blog: [],
    };
  },
  components: {
    Hooper,
    Slide,
    HooperPagination,
    HooperProgress,
  },
  mounted() {
    this.loadOurBlog();
  },
  methods: {
    loadOurBlog() {
      axios.get("/api/bai-viet/moi-nhat").then((response) => {
        this.blog = response.data;
        console.log(response.data);
      });
    },
  },
};
</script>

<style lang="less" scoped>
.hooper-pagination {
  bottom: -30px;
}

.hooper * {
  transition: all 0.3s ease;
}

.hooper {
  height: 290px;
}

.item {
  width: 100%;
  height: 100%;
  position: relative;
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
  .book-image {
    width: 100%;
    height: 80%;
  }
  .book-title {
    width: 100%;
    height: 20%;
    border: 2px solid #fff;
  }
}
.ourblog {
  padding: 3rem 7rem;
  .heading {
    margin-bottom: 2rem;
    text-align: center;
    text-transform: capitalize;
    font-size: 1.5rem;
    font-weight: bold;
    position: relative;
    span {
      padding: 0.5rem 2rem;
      color: #333;
      background: #fff;
      border: 0.1rem solid rgba(0, 0, 0, 0.1);
    }
    &::before {
      content: "";
      position: absolute;
      top: 50%;
      left: 0;
      transform: translateY(-50%);
      width: 100%;
      height: 0.01rem;
      background: rgba(0, 0, 0, 0.1);
      z-index: -1;
    }
  }
}
</style>