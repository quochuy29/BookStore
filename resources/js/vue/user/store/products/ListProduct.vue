<template>
  <div class="home-book">
    <div class="container-book">
      <div class="link-access">
        <router-link class="link" to="/">Trang chủ</router-link> >
        <router-link class="link" :to="{ name: 'books' }">Sản phẩm</router-link>
      </div>
      <div class="book-product">
        <div class="book-sidebar">
          <div class="sidebar">
            <div class="filter-price filter">
              <h4>Khoảng giá</h4>
              <div class="list-price">
                <div
                  class="item-price"
                  :class="{ active: active == 1 }"
                  @click="priceRange(filterPrice.priceLess, 1)"
                >
                  Dưới 40.000
                </div>
                <div
                  class="item-price"
                  :class="{ active: active == 2 }"
                  @click="priceRange(filterPrice.priceMedium, 2)"
                >
                  Từ 40000 đến 100.000
                </div>
                <div
                  class="item-price"
                  :class="{ active: active == 3 }"
                  @click="priceRange(filterPrice.priceMore, 3)"
                >
                  Từ 100000 đến 280.000
                </div>
                <div
                  class="item-price"
                  :class="{ active: active == 4 }"
                  @click="priceRange(filterPrice.priceMax, 4)"
                >
                  Trên 280000
                </div>
              </div>
              <div class="choose-price-text">Chọn khoảng giá</div>
              <div class="input-price-group">
                <input
                  pattern="[0-9]*"
                  v-model="price.min"
                  placeholder="Giá từ"
                  value="0"
                /><span class="space">-</span
                ><input
                  pattern="[0-9]*"
                  v-model="price.max"
                  placeholder="Giá đến"
                  value="0"
                />
              </div>
              <button class="submit-filter" @click="loadProduct(1, 1)">
                Áp dụng
              </button>
            </div>
            <div class="category filter">
              <h4>Danh mục</h4>
              <div class="list-category">
                <div
                  class="item"
                  v-for="(cate, index) in listCate"
                  :key="index"
                >
                  <input
                    type="checkbox"
                    name="category"
                    :id="'category' + index"
                    :value="cate.id"
                    v-model="selected.category"
                  />
                  <div class="name-cate">
                    {{ cate.name }}
                  </div>
                </div>
              </div>
            </div>
            <div class="genres filter">
              <h4>Thể loại sách</h4>
              <div class="list-genres">
                <div
                  class="item"
                  v-for="(genres, index) in listGenres"
                  :key="index"
                >
                  <input
                    type="checkbox"
                    name="genres"
                    :id="'genres' + index"
                    :value="genres.id"
                    v-model="selected.genres"
                  />
                  <div class="name-genres">
                    {{ genres.name }}
                  </div>
                </div>
              </div>
            </div>
            <div class="author filter">
              <h4>Tác giả</h4>
              <div class="list-author">
                <div class="item">
                  <input
                    type="checkbox"
                    name="author"
                    id="author"
                    :value="1"
                    v-model="selected.author"
                    @click="checkOne"
                  />
                  <div class="cate-author">Nhiều tác giả</div>
                </div>
                <div class="item">
                  <input
                    type="checkbox"
                    name="author"
                    id="author"
                    :value="2"
                    v-model="selected.author"
                    @change="checkOne"
                  />
                  <div class="cate-author">Một tác giả</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="book-view">
          <div class="book-sort-bar">
            <span class="sort-bar-text">Sắp xếp theo</span>
            <div class="book-sort-by-option">
              <div
                class="book-sort-option"
                :model="selected.sortBy"
                @click="sortBy('new')"
              >
                Mới nhất
              </div>
              <div class="form-group col-md-4 filter">
                <Multiselect
                  v-model="selected.order"
                  :options="options"
                  :searchable="true"
                  placeholder="Giá"
                  @input="sortBy('price')"
                >
                </Multiselect>
              </div>
            </div>
          </div>
          <div class="list-book">
            <div class="item" v-for="(products, index) in listPro" :key="index">
              <div class="image-book">
                <router-link
                  :to="{ name: 'detailsBook', params: { name: products.slug } }"
                >
                  <img :src="`/storage/${products.image}`" alt="" />
                </router-link>
              </div>
              <div class="name-book">
                <router-link
                  v-if="products.name.split(' ').length > 7"
                  :to="{ name: 'detailsBook', params: { name: products.slug } }"
                  >{{
                    products.name.split(" ").slice(0, 7).join(" ") + "..."
                  }}</router-link
                >
                <router-link
                  v-if="products.name.split(' ').length <= 7"
                  :to="{ name: 'detailsBook', params: { name: products.slug } }"
                  >{{ products.name }}</router-link
                >
              </div>
              <div class="book-detail">
                <router-link
                  class="item-control"
                  :to="{
                    name: 'detailsBook',
                    params: { name: products.slug },
                  }"
                  ><font-awesome-icon :icon="['fas', 'eye']"
                /></router-link>
                <a
                  href="javascript:void(0);"
                  class="item-control"
                  @click="addCart(products.id)"
                  ><font-awesome-icon :icon="['fas', 'cart-shopping']"
                /></a>
                <a
                  href="javascript:void(0);"
                  @click="addWishList(products.id, products.cate_id)"
                  class="item-control"
                  ><font-awesome-icon :icon="['fas', 'heart']"
                /></a>
              </div>
            </div>
          </div>
          <pagination
            class="paginate"
            :data="paginate"
            @pagination-change-page="loadProduct"
          ></pagination>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import cart from "../../../lib/addCart";
import Multiselect from "vue-multiselect";
export default {
  name: "ListProduct",
  data() {
    return {
      listPro: [],
      active: 0,
      listCate: [],
      listGenres: [],
      selected: {
        category: [],
        genres: [],
        page: 1,
        author: [],
        price: [],
        sortBy: "",
        order: "",
        search: "",
      },
      filterPrice: {
        priceLess: {
          max: 40000,
          min: 0,
        },
        priceMedium: {
          max: 100000,
          min: 40000,
        },
        priceMore: {
          max: 280000,
          min: 100000,
        },
        priceMax: {
          min: 280000,
        },
      },
      price: {
        min: 0,
        max: 0,
      },
      paginate: {},
      token: localStorage.getItem("token"),
      options: ["Giá từ thấp đến cao", "Giá từ cao đến thấp"],
    };
  },
  components: {
    Multiselect,
  },
  mounted() {
    this.$root.$on("loadSearch", (text) => {
      this.selected.search = text;
      console.log(text);
    });
    this.checkParam();
    this.loadCategory();
    this.loadGenres();
    this.priceRange();
    this.loadProduct();
  },
  watch: {
    selected: {
      handler: function () {
        this.loadProduct();
      },
      deep: true,
    },
  },
  methods: {
    priceRange(e, id) {
      if (typeof e === "undefined" && typeof id === "undefined") {
        var priceMin = this.$route.query.priceMin
          ? this.$route.query.priceMin
          : 0;
        var priceMax = this.$route.query.priceMax
          ? this.$route.query.priceMax
          : 0;
        Object.keys(this.filterPrice).forEach((el, index) => {
          if (
            this.filterPrice[el].min == priceMin &&
            this.filterPrice[el].max == priceMax
          ) {
            index + 1 == this.active
              ? (this.active = 0)
              : (this.active = index + 1);
          }
        });
      } else {
        id == this.active ? (this.active = 0) : (this.active = id);
        this.selected.price = [];
        if (this.active > 0) {
          if (!e.max) {
            e.max = 0;
          }
          this.selected.price.push(e.min, e.max);
        }
        if(this.price.min == 0 && this.price.max == 0 && this.selected.price.length == 0){
          this.$router.replace()
        }
      }
    },
    sortBy(id) {
      this.selected.sortBy = id;
    },
    checkOne(e) {
      this.selected.author = [];
      if (e.target.checked) {
        this.selected.author.push(e.target.value);
      }
    },
    checkParam() {
      if (this.$route.query.category) {
        if (typeof this.$route.query.category === "string") {
          this.selected.category = this.$route.query.category
            .split("")
            .map(Number);
        } else {
          this.selected.category = this.$route.query.category;
        }
      }
      if (this.$route.query.author) {
        if (typeof this.$route.query.author === "string") {
          this.selected.author = this.$route.query.author.split("").map(Number);
        }
      }

      if (this.$route.query.genres) {
        if (typeof this.$route.query.genres === "string") {
          this.selected.genres = this.$route.query.genres.split("").map(Number);
        } else {
          this.selected.genres = this.$route.query.genres;
        }
      }
    },
    loadProduct(page, type) {
      if (typeof page === "undefined") {
        page = 1;
      } else {
        this.selected.page = page;
      }

      if (!this.$route.query.priceMax) {
        var priceMax = this.price.max;
      } else {
        var priceMax = this.$route.query.priceMax;
      }
      var pathCategory = "";

      if (this.selected.sortBy == "") {
        if (this.$route.query.sortBy) {
          this.selected.sortBy = this.$route.query.sortBy;
          pathCategory += `&sortBy=${this.$route.query.sortBy}`;
        }
      }

      if (this.selected.search == "") {
        if (this.$route.query.search) {
          this.selected.search = this.$route.query.search;
          pathCategory += `&search=${this.$route.query.search}`;
        }
      }

      if (this.selected.order == "") {
        if (this.$route.query.order == "desc") {
          this.selected.order = "Giá từ thấp đến cao";
          pathCategory += `&order=${this.selected.order}`;
        } else if (this.$route.query.order == "asc") {
          this.selected.order = "Giá từ cao đến thấp";
          pathCategory += `&order=${this.selected.order}`;
        }
      }

      if (!this.$route.query.priceMin) {
        var priceMin = this.price.min;
      } else {
        var priceMin = this.$route.query.priceMin;
      }

      var path = "";
      if (this.selected.price.length === 0) {
        if (typeof type === "undefined") {
          path = `/api/product?priceMax=${priceMax}&priceMin=${priceMin}${pathCategory}`;
        } else {
          path = `/api/product?priceMax=${this.price.max}&priceMin=${this.price.min}${pathCategory}`;
        }
      } else {
        if (typeof type === "undefined") {
          path = `/api/product?priceMax=${this.selected.price[1]}&priceMin=${this.selected.price[0]}${pathCategory}`;
        } else {
          path = `/api/product?priceMax=${this.price.max}&priceMin=${this.price.min}${pathCategory}`;
        }
      }

      axios.get(path, { params: this.selected }).then((response) => {
        this.paginate = response.data.data;
        this.listPro = response.data.data.data;
        this.$router.push(response.data.url).catch(() => {});
      });
    },
    loadCategory() {
      axios.get("/api/danh-muc").then((response) => {
        this.listCate = response.data;
      });
    },
    loadGenres() {
      axios.get("/api/the-loai-sach/danh-sach").then((response) => {
        this.listGenres = response.data;
      });
    },
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
  },
};
</script>

<style lang="less" scoped>
.home-book {
  background: #efefef;
  padding: 20px 0;
  .link-access {
    padding-bottom: 20px;
  }
}
.paginate {
  justify-content: center;
  padding-top: 15px;
}
.container-book {
  transition: all 0.3s ease;
  width: 1270px;
  padding-left: 15px;
  padding-right: 15px;
  margin: 0 auto;
  max-width: 100%;
  .book-product {
    max-width: 100%;
    height: 100%;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    .book-sidebar {
      width: 24.9%;
      background: #fff;
      border-radius: 10px 0 0 10px;
      .item-price {
        cursor: pointer;
        background: rgb(238, 238, 238);
        font-size: 13px;
        padding: 2px 12px;
        line-height: 20px;
        display: inline-block;
        position: relative;
        color: rgb(36, 36, 36);
        border-radius: 12px;
        margin-bottom: 4px;
      }
      .item-price.active {
        background: rgba(27, 168, 255, 0.1);
      }
      .choose-price-text {
        color: rgb(133, 133, 133);
        font-size: 13px;
        padding-bottom: 5px;
        margin-top: 4px;
      }
      .input-price-group {
        display: flex;
        align-items: center;
        input {
          flex: 1 1 0%;
          width: 95px;
          height: 30px;
          padding: 0px 8px;
          background: rgb(255, 255, 255);
          border-radius: 4px;
          text-align: left;
          border: 1px solid rgb(184, 184, 184);
          outline: 0px;
          font-size: 13px;
          margin-left: 0;
        }
        .space {
          width: 7px;
          height: 1px;
          font-size: 0px;
          display: inline-block;
          background: rgb(154, 154, 154);
          margin: 0px 4px;
          vertical-align: middle;
        }
      }
      .submit-filter {
        background: rgb(255, 255, 255);
        border: 1px solid rgb(13, 92, 182);
        font-size: 12px;
        color: rgb(13, 92, 182);
        padding: 5px 15px;
        width: 99px;
        margin-top: 8px;
        border-radius: 4px;
      }
      .item {
        display: flex;
        align-items: center;
        .name-cate {
          margin-left: 10px;
        }
        .name-genres {
          margin-left: 10px;
        }

        .cate-author {
          margin-left: 10px;
        }
      }
    }
    .book-view {
      margin: 0 auto;
      background: #fff;
      width: 74.9%;
      border-radius: 0 10px 10px 0;
      .book-sort-bar {
        padding: 20px;
        display: flex;
        justify-content: space-between;
        .sort-bar-text {
          text-align: center;
          height: 40px;
          line-height: 40px;
          padding: 0 10px;
        }
        .book-sort-by-option {
          display: flex;
          flex: 1;
          justify-content: flex-start;
          cursor: pointer;
          .book-sort-option {
            background: #8888;
            text-align: center;
            height: 40px;
            line-height: 40px;
            padding: 0 10px;
            color: #fff;
            &:hover {
              background: #ee4d2d;
            }
          }
        }
        .form-group.col-md-4 {
          height: 40px;
        }
      }
    }
  }
}
.list-book {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  .item {
    width: 25%;
    height: 370px;
    display: flex;
    flex-direction: column;
    align-items: center;
    .image-book {
      width: 100%;
      height: 90%;
      img {
        object-fit: cover;
        width: 100%;
        height: 100%;
        transition: all 0.3s ease;
      }
    }
    .book-detail {
      position: absolute;
      top: 30%;
      right: 0;
      left: 0;
      opacity: 0;
      transition: all 0.3s ease;
      display: flex;
      justify-content: center;
      align-items: center;
      .item-control {
        margin-left: 10px;
        font-size: 1.4rem;
        color: #27bd26;
      }
    }
    &:hover {
      box-shadow: rgb(0 0 0 / 30%) 0px 0px 20px;
      z-index: 1;
      .book-detail {
        top: 50%;
        opacity: 1;
      }
      .image-book {
        img {
          -webkit-filter: sepia(100%);
          filter: sepia(100%);
        }
      }
    }
  }
}

.book-view {
  padding: 20px 0;
}

.book-sidebar {
  padding: 20px 0;
}
</style>