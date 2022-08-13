<template>
  <div class="container">
    <h1>Sản phẩm yêu thích</h1>
    <div class="add-product-wish" v-if="heart">
      <a
        href="javascript:void(0);"
        @click="closeHeart"
        class="close-heart"
      >
        <i class="fa-solid fa-x"></i>
      </a>
      <div class="container">
        <hooper :progress="true" :itemsToShow="5" pagination="fraction">
          <slide
            style="height: 300px !important"
            v-for="(product, index) in productWish"
            :key="index"
          >
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
                  @click="addWishList(product.id, product.cate_id)"
                  class="item-control"
                  ><font-awesome-icon :icon="['fas', 'heart']"
                /></a>
              </div>
            </div>
          </slide>
          <hooper-pagination slot="hooper-addons"></hooper-pagination>
        </hooper>
      </div>
    </div>
    <div class="row">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Tên sản phẩm</th>
            <th>Hình ảnh</th>
            <th>Danh mục</th>
            <th>Giá</th>
            <th>
              <a
                href="javascript:void(0);"
                class="btn btn-success"
                @click="addWish()"
                ><font-awesome-icon :icon="['fas', 'square-plus']"
              /></a>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(wish, index) in wishList" :key="index">
            <td>{{ wish.products.name }}</td>
            <td>
              <img width="70" :src="`/storage/${wish.products.image}`" alt="" />
            </td>
            <td>{{ wish.category.name }}</td>
            <td>{{ wish.products.price }} VNĐ</td>
            <td>
              <a
                class="btn btn-danger"
                href="javascript:void(0);"
                v-on:click="deletePro(wish.id)"
                ><font-awesome-icon :icon="['fas', 'trash-can']"
              /></a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { Hooper, Slide, Pagination as HooperPagination } from "hooper";
import "hooper/dist/hooper.css";
export default {
  name: "wishList",
  data() {
    return {
      wishList: [],
      productWish: [],
      token: localStorage.getItem("token"),
      heart:false
    };
  },
  components: {
    Hooper,
    Slide,
    HooperPagination,
  },
  created() {
    this.loadWish();
    this.loadWishUser();
  },
  methods: {
    loadWish() {
      window.axios.defaults.headers.common[
        "Authorization"
      ] = `Bearer ${this.token}`;
      axios.get("/api/wish-list").then((response) => {
        console.log(response.data);
        this.wishList = response.data;
      });
    },
    deletePro(id) {
      window.axios.defaults.headers.common[
        "Authorization"
      ] = `Bearer ${this.token}`;
      axios.delete("/api/wish-list/delete/"+id).then((response) => {
        if(response.data.status){
          this.$toaster.success(response.data.status)
          this.loadWish();
        }
      });
    },
    addWish() {
      this.heart = true
    },
    addWishList(id,idCate){
      var formData = new FormData();
      formData.append('product_id',id);
      formData.append('cate_id',idCate);
      window.axios.defaults.headers.common[
        "Authorization"
      ] = `Bearer ${this.token}`;
      axios.post("/api/wish-list/add",formData).then((response) => {
        if(response.data.status){
          this.$toaster.success(response.data.status)
          this.loadWish();
        }
      });
      this.loadWishUser();
    },
    loadWishUser() {
      window.axios.defaults.headers.common[
        "Authorization"
      ] = `Bearer ${this.token}`;
      axios.get("/api/wish-list/product-available").then((response) => {
        console.log(response.data);
        this.productWish = response.data;
      });
    },
    closeHeart(){
      this.heart = false
    }
  },
};
</script>

<style lang="less" scoped>
.add-product-wish {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  background: rgba(194, 194, 194, 0.6);
  .hooper {
    margin-top: 50px;
    height: 300px !important;
    .hooper-pagination {
      bottom: -40px;
    }
    .item img {
      height: 100%;
    }
    .item:hover{
      .item-detail{
        top: 0;
      }
    }
  }
  .close-heart{
    position: absolute;
    top: 1%;
    left: 98%;
    bottom: 0;
  }
}
</style>