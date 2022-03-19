<template>
  <div class="container-lg" id="add-form">
    <div class="background"></div>
    <div class="create-form">
      <form
        action="javascript:void(0);"
        @submit="addGenres($event)"
        method="post"
        class="form-product"
        id="addGalleries"
        enctype="multipart/form-data"
      >
        <h1 class="title">Thêm thư viện hình ảnh</h1>
        <div class="form-row">
          <div class="form-group col-md-6 input-product">
            <label for="">Danh mục sản phẩm</label>
            <select
              class="form-select"
              name="product_id"
              id="product"
              v-model="item.product_id"
            >
              <option
                v-for="(pro, index) in products"
                v-bind:key="index"
                :value="pro.id"
              >
                {{ pro.name }}
              </option>
            </select>
            <div
              class="alert alert-danger mt-1"
              :class="{ 'has-error': errors.has('product_id') }"
            >
              {{ errors.first("product_id") }}
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="col-12">
            <div class="form-group">
              <vue-dropzone name="url_image[]" id="upload" :options="config"></vue-dropzone>
            </div>
          </div>
        </div>
        <div class="submit">
          <button type="submit" class="btn btn-primary">Sign in</button>
          <button v-on:click.prevent="Cancel" class="btn btn-danger">
            Cancel
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import vueDropzone from "vue2-dropzone/dist/vue2Dropzone";
import FormErrors from "laravel-form-errors";
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
export default {
  name: "AddGalleries",
  data() {
    return {
      item: {},
      errors: null,
      products: [],
      config: {
        url: "/api/upload",
      },
    };
  },
  components: {
    vueDropzone,
  },
  created() {
    this.errors = new FormErrors();
    axios
      .get("/api/products")
      .then((response) => {
        this.products = response.data;
        console.log(this.products);
      })
      .catch((error) => {
        console.log(error);
      });
  },
  mounted() {
    this.$nextTick(() => {
      $("#product").select2({
        dropdownParent: $("#add-form"),
        placeholder: "Chọn danh mục",
      });
    });
  },
  methods: {
    Cancel() {
      this.$emit("handleAddForm");
    },
    addGenres(e) {
      var formData = new FormData($("#addGalleries")[0]);
      axios
        .post("/api/gallery/store", formData)
        .then((response) => {
          console.log(response)
          if (response.status == 200) {
            this.$emit("handleAddForm");
          }
        })
        .catch((error) => {
          if (error.response.status == 422) {
            this.errors.setMessages(error.response.data.error);
          }
        });
    },
  },
};
</script>

<style lang="css" scoped>
.container-lg {
  visibility: hidden;
  opacity: 0;
  transition: all 0.3s ease;
  z-index: 9999;
  position: fixed;
}

.container-lg.create {
  position: fixed;
  z-index: 9999;
  transition: all 0.3s ease;
  opacity: 1;
  visibility: visible;
}

.create .create-form {
  transform: scale(1);
}

.create-form {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  justify-content: center;
  align-items: center;
}
.form-product {
  background-color: #fff;
  border-radius: 3px;
  overflow-y: scroll;
  overflow-x: hidden;
  height: 340px;
  width: 70%;
  border-radius: 10px;
}

.btn {
  margin: 5px;
}

.form-row {
  display: flex;
  margin: 0 auto;
}

.submit {
  display: flex;
  justify-content: center;
  margin: 10px;
}

.input-product {
  padding: 0 10px;
  width: 50%;
}

.input-border {
  border-radius: 5px;
}

.alert {
  display: none;
  padding: 10px 1rem;
}

.has-error {
  display: block;
}

.background {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: #444;
  opacity: 0.4;
}

.title {
  margin: 0 auto;
  text-align: center;
}
</style>