<template>
  <div class="container">
    <div class="product-related" v-if="loading">
      <a href="javascript:void(0);" @click="closeRelated" class="close-related">
        <i class="fa-solid fa-x"></i>
      </a>
      <div class="d-flex">
        <div class="col-2" v-for="(related, index) in cartProduct" :key="index">
          <div class="img">
            <img :src="`/storage/${related.image}`" />
          </div>
          <div class="name">
            <p class="text-center">{{ related.name }}</p>
          </div>
          <div class="add-cart">
            <button class="btn btn-primary" @click="addCart(related.id)">
              ADD TO CART
            </button>
          </div>
        </div>
      </div>
      <pagination
        class="mt-2 justify-content-center"
        :data="paginate"
        @pagination-change-page="loadProductRelate"
      >
      </pagination>
    </div>
    <h1>Giỏ hàng</h1>
    <div class="row">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th colspan="5">
              Thao tác nhanh
              <input type="hidden" name="clearCart" />
            </th>
            <th>
              <a
                href="javascript:void(0);"
                @click="clearCheck"
                class="btn btn-danger"
                >Remove Check</a
              >
              <a
                href="javascript:void(0);"
                @click="clear"
                class="btn btn-danger"
                >Clear</a
              >
              <router-link v-if="counter > 0" class="btn btn-primary" :to="{ name: 'checkOut'}"
                >Thanh toán</router-link
              >
            </th>
          </tr>
        </thead>
        <thead>
          <tr>
            <th>
              <input
                type="checkbox"
                @click="checkIdCart"
                class="form-check-label"
                id="checkAll"
              />
            </th>
            <th>Tên sản phẩm</th>
            <th>Hình ảnh</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(carts, index) in cart" :key="index">
            <td>
              <input
                type="checkbox"
                class="form-check-label checkId"
                id="checkId"
                name="checkId"
                :value="carts.id"
              />
            </td>
            <td>
              {{ carts.name }}
            </td>
            <td>
              <img
                width="70"
                :src="`/storage/${carts.attributes.image}`"
                alt=""
              />
            </td>
            <td>
              <div class="quantity-cart">
                <p>Số lượng</p>
                <div class="wrapper">
                  <button
                    class="btn-quantity btn--minus"
                    @click="
                      changeCounter('-1', carts.quantity, index, carts.id)
                    "
                    type="button"
                    name="button"
                  >
                    -
                  </button>
                  <input
                    class="quantity"
                    type="text"
                    name="name"
                    v-model="carts.quantity"
                  />
                  <button
                    class="btn-quantity btn--plus"
                    @click="changeCounter('1', carts.quantity, index, carts.id)"
                    type="button"
                    name="button"
                  >
                    +
                  </button>
                </div>
              </div>
            </td>
            <td>
              {{ carts.price.toLocaleString() }}
            </td>
            <td>
              <a
                href="javascript:void(0);"
                @click="deleteCart(carts.id)"
                class="btn btn-danger"
                ><font-awesome-icon :icon="['fas', 'trash-can']"
              /></a>
              <a
                href="javascript:void(0);"
                @click="loadProductRelate(1, carts.id)"
                class="btn btn-success"
              >
                <font-awesome-icon :icon="['fas', 'magnifying-glass']" />
              </a>
            </td>
          </tr>
          <tr>
            <th colspan="5">Tổng đơn hàng</th>
            <th>{{ total }} VNĐ</th>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import cart from "../../../lib/addCart";
export default {
  name: "Cart",
  data() {
    return {
      cart: [],
      cartProduct: [],
      paginate: {},
      loading: false,
      total: 0,
      counter: sessionStorage.getItem("count"),
    };
  },
  created() {
    this.loadCart();
  },
  methods: {
    checkIdCart() {
      $(".checkId").prop("checked", $("#checkAll").prop("checked"));
    },
    clear() {
      axios.delete("/api/gio-hang/clear-to-cart").then((response) => {
        this.$toaster.success(response.data.status);
        sessionStorage.setItem("count", response.data.counter);
        setTimeout(
          () => this.$root.$emit("myEvent", sessionStorage.getItem("count")),
          500
        );
        this.loadCart();
      });
    },
    clearCheck() {
      var formData = new FormData();
      var allId = [];
      $("input:checkbox[name=checkId]:checked").each(function () {
        allId.push($(this).val());
      });
      formData.append("idCart", allId);
      axios
        .post("/api/gio-hang/clear-check-cart", formData)
        .then((response) => {
          this.$toaster.success(response.data.status);
          sessionStorage.setItem("count", response.data.counter);
          setTimeout(
            () => this.$root.$emit("myEvent", sessionStorage.getItem("count")),
            500
          );
          this.loadCart();
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
      this.loadCart();
    },
    loadCart() {
      axios.get("/api/gio-hang/cart").then((response) => {
        this.cart = response.data.content;
        this.total = response.data.total;
      });
    },
    closeRelated() {
      localStorage.removeItem("id");
      this.loading = false;
    },
    loadProductRelate(page, id) {
      if (typeof id === "undefined") {
        if (localStorage.getItem("id")) {
          id = localStorage.getItem("id");
        } else {
          id = 0;
        }
      } else {
        localStorage.setItem("id", id);
      }
      axios
        .get("/api/product/related/" + id + "?page=" + page)
        .then((response) => {
          if (response.data.data) {
            this.loading = true;
          }
          this.cartProduct = response.data.data;
          this.paginate = response.data;
          console.log(response.data);
        });
    },
    deleteCart(id) {
      axios.delete("/api/gio-hang/delete-to-cart/" + id).then((response) => {
        this.$toaster.success(response.data.status);
        sessionStorage.setItem("count", response.data.counter);
        setTimeout(
          () => this.$root.$emit("myEvent", sessionStorage.getItem("count")),
          500
        );
        this.loadCart();
      });
    },
    changeCounter: function (num, counter, index, id) {
      counter = parseInt(counter) + parseInt(num);
      !isNaN(counter) && counter > 0 ? counter : (counter = 0);
      this.cart[index]["quantity"] = counter;
      axios
        .get("/api/gio-hang/edit-to-cart/" + id + "?quantity=" + counter)
        .then((response) => {
          if (response.data.counter < counter) {
            this.cart[index]["quantity"] = response.data.counter;
            this.$toaster.warning(response.data.status);
          } else {
            this.cart[index]["quantity"] = response.data.counter;
            this.$toaster.success(response.data.status);
          }
          this.total = response.data.total;
        });
    },
  },
};
</script>

<style lang="less" scoped>
.row {
  img {
    height: 105px;
    object-fit: cover;
  }
}
.product-related {
  position: fixed;
  z-index: 999999;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(231, 224, 227, 0.8);
  .close-related {
    position: absolute;
    top: 1%;
    right: 0;
    left: 98%;
    bottom: 0;
    font-size: 1.6rem;
  }
  .d-flex {
    margin-top: 90px;
    .col-2 {
      position: relative;
      width: 100%;
      height: 100%;
      .img {
        height: 100%;
        img {
          width: 100%;
          height: 270px !important;
          object-fit: cover;
        }
      }
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
</style>