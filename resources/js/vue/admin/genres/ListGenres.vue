<template>
  <div class="list-product">
    <div class="content-header">
      <div class="container-fluid pd-1">
        <div class="card card-secondary my-0">
          <div class="card-header">
            <ol class="breadcrumb float-sm-left">
              <li class="breadcrumb-item card-title">Danh sách danh mục</li>
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
            <add-genres
              :key="isCreate"
              v-on:handleAddForm="handleAddForm"
              v-bind:class="isCreate == 'OpenCreate' ? 'create' : ''"
            />
            <edit-genres
              :key="isEdit"
              v-on:handleEditForm="handleEditForm"
              v-bind:updateItem="updateItem"
              v-bind:class="isEdit == 'OpenEdit' ? 'edit' : ''"
            />
            <h1 class="title">Danh sách thể loại</h1>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="">Trạng thái</label>
                  <select
                    class="form-select"
                    @change="selectCate($event)"
                    v-model="filters.isActive"
                    name="isActive"
                    id="active"
                  >
                    <option value="">Lấy tất cả</option>
                    <option value="1">Hoạt động</option>
                    <option value="0">Không kích hoạt</option>
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="">Sắp xếp</label>
                  <select
                    class="form-select"
                    @change="selectCate($event)"
                    v-model="filters.order"
                    name="order"
                    id="order"
                  >
                    <option value="">Lấy tất cả</option>
                    <option value="1">Tên tăng dần</option>
                    <option value="2">Tên giảm dần</option>
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
                    v-model="filters.keyword"
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
                        <th scope="col">Số thứ tự</th>
                        <th scope="col">Danh mục</th>
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
                      <tr v-for="(cate, index) in item" v-bind:key="index">
                        <th scope="row">{{ index }}</th>
                        <td>{{ cate.name }}</td>
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
import AddGenres from "./AddGenres.vue";
import EditGenres from "./EditGenres.vue";
export default {
  name: "Genres",
  data() {
    return {
      item: [],
      updateItem: {},
      isEdit: 'CloseEdit',
      isCreate: 'CloseCreate',
      category: [],
      filters: {
        isActive: "",
        keyword: "",
        order: "",
      },
      paginate: {},
    };
  },
  components: {
    AddGenres,
    EditGenres,
  },
  methods: {
    selectCate(page) {
      if (typeof page === "undefined") {
        page = 1;
      }
      axios
        .get(
          "/api/genres?page=" +
            page +
            "&isActive=" +
            this.filters.isActive +
            "&order=" +
            this.filters.order +
            "&search=" +
            this.filters.keyword
        )
        .then((response) => {
          this.paginate = response.data;
          this.item = response.data.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    handleConfirm() {
      this.isCreate = 'OpenCreate';
    },
    handleAddData() {
      this.selectCate();
    },
    handleEditForm() {
      this.isEdit = 'CloseEdit';
      this.selectCate();
    },
    handleDelete() {
      this.selectCate();
    },
    openCreate() {
      this.isCreate = 'OpenCreate';
    },
    handleAddForm() {
      this.isCreate = 'CloseCreate';
      this.selectCate();
    },
    updateForm(id) {
      axios
        .get("/api/genres/updateForm/" + id)
        .then((response) => {
          this.isEdit = 'OpenEdit';
          this.updateItem = response.data;
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
  created() {
    this.selectCate();
  },
};
</script>

<style scoped>
.card-secondary:not(.card-outline) > .card-header {
  background-color: #fff;
  color: #333;
}
.content {
  padding: 0 0.5rem;
}
</style>