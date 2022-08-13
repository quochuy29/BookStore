<template>
  <section class="featured" id="featured">
    <h1 class="heading"><span>featured books</span></h1>
    <hooper
      :progress="true"
      :infiniteScroll="true"
      :itemsToShow="5"
      pagination="fraction"
    >
      <slide v-for="(product, index) in products" :key="index">
        <div class="item">
          <img
            :src="`/storage/${product.image}`"
            :alt="product.name"
            :title="product.name"
          />
          <div class="item-detail">
            <router-link
              class="item-control"
              :to="{
                name: 'detailsBook',
                params: { name: product.slug },
              }"
              ><font-awesome-icon :icon="['fas', 'eye']"
            /></router-link>
            <a
              href="javascript:void(0);"
              class="item-control"
              @click="addCart(product.id)"
              ><font-awesome-icon :icon="['fas', 'cart-shopping']"
            /></a>
            <a
              href="javascript:void(0);"
              @click="addWishList(product.id, product.cate_id)"
              class="item-control"
              ><font-awesome-icon :icon="['fas', 'heart']"
            /></a>
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
import cart from "../../../lib/addCart";
export default {
  name: "FeaturedBook",
  data() {
    return {
      products: [],
      token: localStorage.getItem("token"),
    };
  },
  components: {
    Hooper,
    Slide,
    HooperPagination,
    HooperProgress,
  },
  created() {
    axios.get("/api/product/featured").then((response) => {
      this.products = response.data;
      console.log(response.data);
    });
  },
  methods: {
    addCart(id) {
      var formData = new FormData();
      formData.append("quantity", 1);
      cart.foo(id, formData);
      setTimeout(
        () => this.$root.$emit("myEvent", sessionStorage.getItem("count")),
        500
      );
    },
    addWishList(id, idCate) {
      if (this.token) {
        var formData = new FormData();
        formData.append("product_id", id);
        formData.append("cate_id", idCate);
        window.axios.defaults.headers.common[
          "Authorization"
        ] = `Bearer ${this.token}`;
        axios.post("/api/wish-list/add", formData).then((response) => {
          if (response.data.status) {
            this.$toaster.success(response.data.status);
          }
        });
      } else {
        this.$toaster.warning('Đăng nhập để thêm sản phẩm yêu thích')
      }
    },
  },
};
</script>

<style lang="less">
.hooper-pagination {
  bottom: -30px;
}

.hooper * {
  transition: all 0.3s ease;
}

.hooper {
  height: 330px;
}

.item {
  width: 100%;
  height: 100%;
  position: relative;
  transition: all 0.3s ease;
  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border: 2px solid #fff;
  }
  .item-detail {
    position: absolute;
    top: -100%;
    height: 100%;
    right: 0;
    background-color: rgba(176, 176, 176, 0.3);
    left: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    .item-control {
      font-size: 1.4rem;
      color: #333;
    }
  }
  &:hover {
    .item-detail {
      top: 0;
    }
  }
}
.featured {
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