<template>
  <div class="">
    <div class="product-exist" v-if="productDetail == null">
      <img src="/storage/image/a60759ad1dabe909c46a817ecbf71878.png" alt="" />
      <p>Oops , Sản phẩm này không tồn tại !</p>
    </div>
    <div class="container" v-if="productDetail != null">
      <div class="show-galleries" v-if="gallery">
        <a
          href="javascript:void(0);"
          @click="closeGallery"
          class="close-galleries"
        >
          <i class="fa-solid fa-x"></i>
        </a>
        <hooper :itemsToShow="4" pagination="fraction">
          <slide>
            <div class="item">
              <img :src="`/storage/${productDetail.image}`" />
            </div>
          </slide>
          <slide
            v-for="(gallery, index) in productDetail.galleries"
            :key="index"
          >
            <div class="item">
              <img :src="`/storage/${gallery.url_image}`" />
            </div>
          </slide>
          <hooper-pagination slot="hooper-addons"></hooper-pagination>
        </hooper>
      </div>
      <div class="link-access" v-if="loading">
        <router-link class="link" to="/">Trang chủ</router-link> >
        <router-link class="link" :to="productDetail.categories.name">{{
          productDetail.categories.name
        }}</router-link>
        >
        <router-link class="link" :to="productDetail.slug">{{
          productDetail.name
        }}</router-link>
      </div>
      <div class="row d-flex">
        <div class="content-img col-5">
          <div class="img-book">
            <img
              id="img-product"
              :src="`/storage/${productDetail.image}`"
              alt=""
            />
          </div>
          <div class="gallries-book mt-1">
            <div class="galleries-img">
              <img
                class="img-gallery"
                :src="`/storage/${productDetail.image}`"
                @click="changeImg(productDetail.image)"
              />
            </div>
            <div
              class="galleries-img"
              v-for="(gallery, index) in productDetail.galleries"
              :key="index"
            >
              <img
                v-if="index <= 2"
                :class="`img-gallery img-${index}`"
                :src="`/storage/${gallery.url_image}`"
                alt=""
                @click="changeImg(gallery.url_image)"
              />
              <div class="img-add" v-if="index == 2">
                <a href="javascript:void(0);" @click="galleries"
                  ><p class="text">Xem thêm</p></a
                >
              </div>
            </div>
          </div>
        </div>
        <div class="content-text col-7">
          <div class="content-text-detail">
            <div class="name-product">
              {{ productDetail.name }}
            </div>
            <div class="category-product" v-if="loading">
              <router-link
                :to="`/san-pham?category=${productDetail.categories.id}`"
                >{{ productDetail.categories.name }}</router-link
              >
            </div>
            <router-link
              class="badge badge-success"
              v-for="(genres, index) in genresProduct"
              :key="index"
              :to="`/san-pham?genres=${genres.id}`"
              >{{ genres.name }}</router-link
            >
            <div class="author">
              Tác giả :
              <span
                class="badge badge-primary"
                v-for="(author, index) in authorProduct"
                :key="index"
                >{{ author.name }}</span
              >
            </div>
            <div class="count-buy d-flex">
              <div class="rate-product">5 sao</div>
              <div class="buy">(đã bán : {{ countBuy }} sản phẩm)</div>
            </div>
            <div class="price">
              <p>{{ productDetail.price }} đ</p>
              <span>Mua xong đéo được đổi trả .</span>
            </div>
            <div class="quantity-cart">
              <p>Số lượng</p>
              <div class="wrapper">
                <button
                  class="btn-quantity btn--minus"
                  @click="changeCounter('-1')"
                  type="button"
                  name="button"
                >
                  -
                </button>
                <input
                  class="quantity"
                  type="text"
                  name="name"
                  v-model="counter"
                />
                <button
                  class="btn-quantity btn--plus"
                  @click="changeCounter('1')"
                  type="button"
                  name="button"
                >
                  +
                </button>
              </div>
            </div>
            <div class="add-cart">
              <button
                class="btn btn-primary"
                @click="addCart(productDetail.id)"
              >
                ADD TO CART
              </button>
            </div>
          </div>
        </div>
      </div>
      <h3>Sản phẩm tương tự</h3>
      <hooper :itemsToShow="4" :infiniteScroll="true" pagination="fraction">
        <slide v-for="(related, index) in productRelated" :key="index">
          <div class="item">
            <img :src="`/storage/${related.image}`" />
            <div class="item-detail">
              <router-link
                class="item-control"
                :to="{
                  name: 'detailsBook',
                  params: { name: related.slug },
                }"
                ><font-awesome-icon :icon="['fas', 'eye']"
              /></router-link>
              <a
                href="javascript:void(0);"
                class="item-control"
                @click="addToCart(related.id)"
                ><font-awesome-icon :icon="['fas', 'cart-shopping']"
              /></a>
              <a
                href="javascript:void(0);"
                @click="addWishList(related.id, related.cate_id)"
                class="item-control"
                ><font-awesome-icon :icon="['fas', 'heart']"
              /></a>
            </div>
          </div>
        </slide>
        <hooper-navigation slot="hooper-addons"></hooper-navigation>
        <hooper-progress slot="hooper-addons"></hooper-progress>
      </hooper>
    </div>
  </div>
</template>

<script>
import {
  Hooper,
  Slide,
  Navigation as HooperNavigation,
  Progress as HooperProgress,
} from "hooper";
import "hooper/dist/hooper.css";
import Page404 from "../../../errors/404.vue";
export default {
  name: "DetailBooks",
  props: {
    name: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      productDetail: {},
      loading: false,
      gallery: false,
      counter: 1,
      productRelated: [],
      id: 0,
      token: localStorage.getItem("token"),
      genresProduct: [],
      authorProduct: [],
      maxIndex: 0,
      countBuy: 0,
    };
  },
  components: {
    Hooper,
    Slide,
    HooperNavigation,
    HooperProgress,
    Page404,
  },
  created() {
    this.loading = false;
    this.loadProductDetail();
  },
  methods: {
    loadProductDetail() {
      axios.get("/api/product/detail/" + this.name).then((response) => {
        this.loading = true;
        this.productDetail = response.data.data;
        if (parseInt(response.data.count.toString()) > 0) {
          this.countBuy = parseInt(response.data.count.toString());
        }
        axios
          .get("/api/product/related-product-detail/" + this.productDetail.id)
          .then((response) => {
            this.loading = true;
            this.productRelated = response.data.data;
          });
        axios
          .get("/api/product/related-product-detail/" + this.productDetail.id)
          .then((response) => {
            this.loading = true;
            this.productRelated = response.data.data;
          });
        axios
          .get("/api/the-loai-sach/" + this.productDetail.id)
          .then((response) => {
            this.genresProduct = response.data;
          });
        axios
          .get("/api/tac-gia-sach/" + this.productDetail.id)
          .then((response) => {
            this.authorProduct = response.data;
          });
      });
    },
    addCart(id) {
      var formData = new FormData();
      formData.append("quantity", this.counter);
      axios
        .post("/api/gio-hang/add-to-cart/" + id, formData)
        .then((response) => {
          console.log(response.data);
          sessionStorage.setItem("count", response.data.count);
          this.$root.$emit("myEvent", sessionStorage.getItem("count"));
        });
    },
    addToCart(id) {
      var formData = new FormData();
      formData.append("quantity", 1);
      cart.foo(id, formData);
      setTimeout(
        () => this.$root.$emit("myEvent", sessionStorage.getItem("count")),
        500
      );
    },
    addWishList(id, idCate) {
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
    },
    galleries() {
      this.gallery = true;
    },
    closeGallery() {
      this.gallery = false;
    },
    changeImg(url) {
      $(`#img-product`).attr("src", `/storage/${url}`);
    },
    changeCounter: function (num) {
      this.counter += +num;
      if (this.counter > this.productDetail.quantity) {
        this.counter = this.productDetail.quantity;
        this.$toaster.warning(
          `Số lượng sản phẩm còn lại là ${this.productDetail.quantity}`
        );
      }
      !isNaN(this.counter) && this.counter > 0
        ? this.counter
        : (this.counter = 0);
    },
  },
};
</script>

<style lang="less" scoped>
.product-exist{
  text-align: center;
  img{
    width: 115px;
  }
}
.badge {
  margin-right: 3px;
}
.container {
  .show-galleries {
    position: fixed;
    background: rgba(231, 224, 227, 0.8);
    width: 100%;
    height: 100%;
    top: 0;
    bottom: 0;
    z-index: 9999;
    left: 0;
    right: 0;
    .close-galleries {
      position: absolute;
      top: 1%;
      right: 0;
      left: 98%;
      bottom: 0;
      font-size: 1.6rem;
    }
    .hooper {
      margin-top: 70px;
      .item {
        width: 90%;
        height: 100%;
        position: relative;
        margin: 0 auto;
        img {
          width: 100%;
          height: 90%;
          object-fit: contain;
        }
      }
    }
  }
  .link-access {
    .link {
      color: #556666;
      &:hover {
        text-decoration: underline;
      }
    }
  }
}

.row {
  .content-img {
    .img-book {
      width: 400px;
      img {
        width: 100%;
      }
    }
  }
  .gallries-book {
    width: 400px;
    display: flex;
    position: relative;
    .galleries-img {
      width: 33.3333333%;
      img {
        width: 100px;
        height: 100%;
        object-fit: cover;
      }
      .img-add {
        width: 100px;
        position: absolute;
        top: 0;
        left: 75%;
        bottom: 0;
        right: 0;
        background: rgba(50, 48, 48, 0.38);
        .text {
          color: #fff;
          position: absolute;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          width: 100%;
          height: 100%;
          display: flex;
          justify-content: center;
          align-items: center;
        }
      }
    }
  }
}

.content-text-detail {
  .price {
    width: 100%;
    height: 70px;
    background: #5666;
    display: flex;
    flex-direction: column;
    border-radius: 10px;
    p {
      padding-left: 10px;
      margin-bottom: 0;
      font-size: 1.6rem;
      font-weight: bold;
    }
    span {
      padding-left: 10px;
    }
  }
}

.wrapper {
  height: 30px;
  display: flex;
  p {
    margin-bottom: 0;
  }
}
.quantity {
  -webkit-appearance: none;
  border: none;
  text-align: center;
  width: 30px;

  font-size: 16px;
  color: #43484d;
  font-weight: 300;
  border: 1px solid #e1e8ee;
}

.btn-quantity {
  border: 1px solid #e1e8ee;
  width: 30px;
  background-color: #e1e8ee;
  border-radius: 0;
  cursor: pointer;
}
button:focus,
input:focus {
  outline: 0;
}

.add-cart {
  margin-top: 5px;
  button {
    height: 40px;
    width: 136px;
    border-radius: 20px;
    background: rgba(159, 226, 250, 0.8);
    border: none;
  }
  &:hover {
    button {
      background: rgba(27, 163, 211, 0.8);
    }
  }
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
  &-detail {
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
</style>