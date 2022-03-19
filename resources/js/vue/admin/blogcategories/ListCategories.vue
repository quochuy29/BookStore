<template>
  <div class="list-blogCategories">
    <div class="content-header">
      <div class="container-fluid pd-1">
        <div class="card card-secondary my-0">
          <div class="card-header">
            <ol class="breadcrumb float-sm-left">
              <li class="breadcrumb-item card-title">
                Danh sách danh mục bài viết
              </li>
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
            <add-cate-blog
              :key="isCreate"
              v-on:handleAddForm="handleAddForm"
              v-bind:class="isCreate == 'OpenCreate' ? 'create' : ''"
              v-on:closeForm="closeForm"
            />
            <edit-cate-blog
              :key="isEdit"
              v-bind:class="isEdit == 'OpenEdit' ? 'edit' : ''"
              v-bind:updateItem="updateItem"
              v-on:handleEditForm="handleEditForm"
            />
            <h1>Danh sách danh mục bài viết</h1>
            <div class="row">
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
                        <th scope="col">Tên danh mục bài viết</th>
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
                      <tr v-for="(blogCate, index) in item" v-bind:key="index">
                        <th scope="row">{{ index }}</th>
                        <td>{{ blogCate.name }}</td>
                        <td>
                          <a
                            class="btn btn-primary"
                            href="javascript:void(0);"
                            v-on:click="updateForm(blogCate.id)"
                            ><font-awesome-icon
                              :icon="['fas', 'pen-to-square']"
                          /></a>
                          <a
                            class="btn btn-primary"
                            href="javascript:void(0);"
                            v-on:click="deleteCateBlog(blogCate.id)"
                            ><font-awesome-icon :icon="['fas', 'trash-can']"
                          /></a>
                          <router-link
                            class="btn btn-primary"
                            :to="{
                              name: 'details',
                              params: { id: blogCate.id },
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
    </section>
  </div>
</template>

<script>
import AddCateBlog from './AddCategories.vue';
import EditCateBlog from './EditCategories.vue';
export default {
  name: "ListCateBlog",
  data() {
    return {
      item: [],
      isEdit: "CloseEdit",
      updateItem: {},
      isCreate: "CloseCreate",
      paginate: {},
      filter: {
        cate: "",
        keyword: "",
      },
    };
  },
  components: {
    AddCateBlog,
    EditCateBlog,
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
  },
  methods: {
    selectCate(page) {
      if (typeof page === "undefined") {
        page = 1;
      }
      axios
        .get(
          "/api/blog-categories?page=" +
            page +
            "&category=" +
            this.filter.cate +
            "&order=" +
            this.filter.order +
            "&search=" +
            this.filter.keyword
        )
        .then((response) => {
          this.paginate = response.data;
          this.item = response.data.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    handleEditForm() {
      this.isEdit = "CloseEdit";
      this.selectCate();
    },
    openCreate() {
      this.isCreate = "OpenCreate";
    },
    handleAddForm() {
      this.isCreate = "CloseCreate";
      this.selectCate();
    },
    closeForm() {
      this.isCreate = "CloseCreate";
    },
    updateForm(id) {
      axios
        .get("/api/blog-categories/updateForm/" + id)
        .then((response) => {
          this.isEdit = "OpenEdit";
          this.updateItem = response.data;
          console.log(response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    deleteCateBlog(id) {
      if (confirm("Bạn có muốn xoá danh mục bài viết này không ?")) {
        axios
          .delete("/api/blog-categories/delete/" + id)
          .then((response) => {
            this.selectCate();
            this.$toaster.success(response.data.status);
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
</style>