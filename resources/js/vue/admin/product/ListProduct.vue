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
            <add-product
              :key="isCreate"
              v-on:handleAddForm="handleAddForm"
              v-bind:class="isCreate == 'OpenCreate' ? 'create' : ''"
              v-on:closeForm="closeForm"
            />
            <edit-product
              :key="isEdit"
              v-bind:class="isEdit == 'OpenEdit' ? 'edit' : ''"
              v-bind:updateItem="updateItem"
              v-on:handleEditForm="handleEditForm"
            />
            <h1>Danh sách sản phẩm</h1>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="">Danh mục</label>
                  <select
                    class="form-select"
                    @change="selectCate($event)"
                    v-model="filter.cate"
                    name="cate"
                    id="cate"
                  >
                    <option value="">Lấy tất cả</option>
                    <option
                      v-for="(cate, index) in categories"
                      v-bind:key="index"
                      :value="cate.id"
                    >
                      {{ cate.name }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="">Sắp xếp</label>
                  <select
                    class="form-select"
                    @change="selectCate($event)"
                    v-model="filter.order"
                    name="order"
                    id="order"
                  >
                    <option value="">Lấy tất cả</option>
                    <option value="1">Tên tăng dần</option>
                    <option value="2">Tên giảm dần</option>
                    <option value="3">Giá tăng dần</option>
                    <option value="4">Giá giảm dần</option>
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
                      Search
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
                        <th scope="col">Tên sản phẩm</th>
                        <th scope="col">Danh mục</th>
                        <th scope="col">Giá</th>
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
                      <tr v-for="(pro, index) in item" v-bind:key="index">
                        <th scope="row">{{ index }}</th>
                        <td>{{ pro.name }}</td>
                        <td>{{ pro.categories.name }}</td>
                        <td>{{ pro.price }}</td>
                        <td>
                          <a
                            class="btn btn-primary"
                            href="javascript:void(0);"
                            v-on:click="updateForm(pro.id)"
                            ><font-awesome-icon
                              :icon="['fas', 'pen-to-square']"
                          /></a>
                          <a
                            class="btn btn-primary"
                            href="javascript:void(0);"
                            v-on:click="deletePro(pro.id)"
                            ><font-awesome-icon :icon="['fas', 'trash-can']"
                          /></a>
                          <router-link
                            class="btn btn-primary"
                            :to="{
                              name: 'details',
                              params: { id: pro.id },
                            }"
                            ><font-awesome-icon :icon="['fas', 'eye']"
                          /></router-link>
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
import AddProduct from "./AddProduct.vue";
import EditProduct from "./EditProduct.vue";
import DetailPoduct from "./DetailProduct.vue";
export default {
  name: "product",
  data() {
    return {
      item: [],
      isEdit: 'CloseEdit',
      updateItem: {},
      isCreate: 'CloseCreate',
      categories: [],
      paginate: {},
      filter: {
        cate: "",
        order: "",
        keyword: "",
      },
      // fields: [
      //   {
      //     key: "name",
      //     label: "Tên sản phẩm",
      //     sortable: true,
      //   },
      //   {
      //     key: "categories.name",
      //     label: "Tên danh mục",
      //     sortable: false,
      //   },
      //   {
      //     key: "price",
      //     label: "Giá sản phẩm",
      //     sortable: true,
      //   },
      //   { key: "action" },
      // ],
      // filters: null,
      // filterOn: [],
      // totalRows: 0,
      // currentPage: 0,
      // perPage: 0,
    };
  },
  components: {
    AddProduct,
    EditProduct,
    DetailPoduct,
  },
  computed: {
    Create: function () {
      return {
        create: this.isCreate,
      };
    },
  },
  mounted() {
    this.selectCate();
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
  created() {
    axios.get("/api/categories").then((response) => {
      this.categories = response.data;
    });
  },
  methods: {
    selectCate(page) {
      if (typeof page === "undefined") {
        page = 1;
      }
      axios
        .get(
          "/api/products?page=" +
            page +
            "&category=" +
            this.filter.cate +
            "&order=" +
            this.filter.order +
            "&search=" +
            this.filter.keyword
        )
        .then((response) => {
          // this.totalRows = 1;
          // this.currentPage = 1;
          // this.perPage = response.per_page;
          this.paginate = response.data;
          this.item = response.data.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    handleEditForm() {
      this.isEdit = 'CloseEdit';
      this.selectCate();
    },
    openCreate() {
      this.isCreate = 'OpenCreate';
    },
    handleAddForm() {
      this.isCreate = 'CloseCreate';
      this.selectCate();
    },
    closeForm() {
      this.isCreate = 'CloseCreate';
    },
    updateForm(id) {
      axios
        .get("/api/products/updateForm/" + id)
        .then((response) => {
          this.isEdit = 'OpenEdit';
          this.updateItem = response.data;
          this.categories.forEach((el) => {
            if (el.id == response.data.cate_id) {
              $("#select2-category-edit-container").text(el.name);
            }
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },
    deletePro(id) {
      if (confirm("Bạn có muốn xoá sản phẩm này không ?")) {
        axios
          .delete("/api/products/delete/" + id)
          .then((response) => {
            this.selectCate();
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
  },
};
</script>

<style lang="less" scoped>
.card-secondary:not(.card-outline) > .card-header {
  background-color: #fff;
  color: #333;
}
.content {
  padding: 0 0.5rem;
}

button:not(:disabled) {
    background: #1e7e34;
    color: #fff;
}
</style>