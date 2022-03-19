<template>
  <div class="list-product">
    <div class="content-header">
      <div class="container-fluid pd-1">
        <div class="card card-secondary my-0">
          <div class="card-header">
            <ol class="breadcrumb float-sm-left">
              <li class="breadcrumb-item card-title">Danh sách sản phẩm</li>
            </ol>
          </div>
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid pb-1">
        <div class="card card-outline">
          <div class="card-body">
            <add-gallery
              v-on:handleAddForm="handleAddForm"
              v-bind:class="isCreate == true ? 'create' : ''"
              v-on:closeForm="closeForm"
            />
            <edit-gallery
              v-bind:class="isEdit == true ? 'edit' : ''"
              v-bind:updateItem="updateItem"
              v-on:handleEditForm="handleEditForm"
            />
            <h1>Danh sách sản phẩm</h1>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="">Sắp xếp</label>
                  <select
                    class="form-select"
                    @change="selectCate($event)"
                    v-model="filter.product"
                    name="product"
                    id="product"
                  >
                    <option value="">Lấy tất cả</option>
                    <option
                      v-for="(product, index) in products"
                      :key="index"
                      :value="product.id"
                    >
                      {{ product.name }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="col-4">
                <label for="">Tìm kiếm</label>
                <div class="input-group mb-3">
                  <input
                    type="text"
                    @keyup.enter="selectCate($event)"
                    class="form-control"
                    v-model="filter.keyword"
                  />
                  <div class="input-group-append">
                    <button
                      @click="selectCate($event)"
                      class="input-group-text"
                      id="basic-addon2"
                    >
                      search
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="row p-1">
              <div style="width: 100%">
                <div class="table-responsive">
                  <table
                    class="table table-bordered data-table"
                    style="width: 100%"
                  >
                    <thead>
                      <tr>
                        <th scope="col">STT</th>
                        <th scope="col">Hình ảnh</th>
                        <th scope="col">Sản phẩm</th>
                        <th scope="col">
                          <button
                            type="button"
                            v-on:click="openCreate"
                            class="btn btn-success"
                          >
                            <font-awesome-icon :icon="['fas', 'square-plus']" />
                          </button>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(gallery, index) in galleries" :key="index">
                        <th scope="row">{{ index }}</th>
                        <td>
                          <img :src="`/storage/${gallery.url_image}`" alt="" />
                        </td>
                        <td>{{ gallery.products.name }}</td>
                        <td>
                          <a
                            href="javascript:void(0);"
                            class="btn btn-primary"
                            v-on:click="updateForm(cate.id)"
                            ><font-awesome-icon
                              :icon="['fas', 'pen-to-square']"
                          /></a>
                          <a
                            href="javascript:void(0);"
                            class="btn btn-danger"
                            v-on:click="deleteCate(cate.id)"
                            ><font-awesome-icon :icon="['fas', 'trash-can']"
                          /></a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <pagination
                    :data="paginate"
                    @pagination-change-page="selectCate"
                  ></pagination>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->
    </section>
  </div>
</template>

<script>
import AddGallery from "./AddGallery.vue";
import EditGallery from "./EditGallery.vue";
export default {
  name: "ListGallery",
  data() {
    return {
      item: [],
      isEdit: false,
      updateItem: {},
      isCreate: false,
      galleries: [],
      products: [],
      paginate: {},
      filter: {
        product: "",
        keyword: "",
      },
      isDetail: false,
    };
  },
  components: {
    AddGallery,
    EditGallery,
  },
  created() {
    this.selectCate();
    axios.get("/api/products").then((response) => {
      this.products = response.data;
    });
  },
  mounted() {
    $("select").map((el, dom) => {
      var idSelect = $(dom).attr("id");
      $("#" + idSelect)
        .select2({
          placeholder: "Select " + idSelect,
        })
        .on("select2:select", function (e) {
          var event = new Event("change");
          e.target.dispatchEvent(event);
        });
    });
  },
  methods: {
    selectCate(page) {
      if (typeof page === "undefined") {
        page = 1;
      }
      axios
        .get(
          "/api/gallery?page=" +
            page +
            "&product=" +
            this.filter.product +
            "&search=" +
            this.filter.keyword
        )
        .then((response) => {
          console.log(response.data)
          this.paginate = response.data;
          this.galleries = response.data.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    handleConfirm() {
      this.isCreate = true;
    },
    handleAddData() {
      this.selectCate();
    },
    handleEditForm() {
      this.isEdit = false;
      this.selectCate();
    },
    closeForm() {},
    handleDelete() {
      this.selectCate();
    },
    openCreate() {
      this.isCreate = true;
    },
    handleAddForm() {
      this.isCreate = false;
      this.selectCate();
    },
    updateForm(id) {
      axios
        .get("/api/genres/updateForm/" + id)
        .then((response) => {
          this.isEdit = true;
          this.updateItem = response.data;
          tinymce.get("detail").setContent(response.data.detail);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    deleteCate(id) {
      axios
        .delete("/api/genres/delete/" + id)
        .then((response) => {
          this.selectCate();
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>

<style lang="css" scoped>
.card-secondary:not(.card-outline) > .card-header {
  background-color: #fff;
  color: #333;
}
.content {
  padding: 0 0.5rem;
}
</style>