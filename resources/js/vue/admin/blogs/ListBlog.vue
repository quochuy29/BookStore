<template>
  <div class="list-blog">
    <div class="content-header">
      <div class="container-fluid pd-1">
        <div class="card card-secondary my-0">
          <div class="card-header">
            <ol class="breadcrumb float-sm-left">
              <li class="breadcrumb-item card-title">Danh sách bài viết</li>
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
            <add-blog
              :key="isCreate"
              v-on:handleAddForm="handleAddForm"
              v-bind:class="isCreate == 'OpenCreate' ? 'create' : ''"
              v-on:closeForm="closeForm"
            />
            <edit-blog
              :key="isEdit"
              v-bind:class="isEdit == 'OpenEdit' ? 'edit' : ''"
              v-bind:updateItem="updateItem"
              v-on:handleEditForm="handleEditForm"
            />
            <h1>Danh sách bài viết</h1>
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
                        <th scope="col">Tiêu đề bài viết</th>
                        <th scope="col">Danh mục bài viết</th>
                        <th scope="col">Hình ảnh</th>
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
                      <tr v-for="(blog, index) in item" v-bind:key="index">
                        <th scope="row">{{ index }}</th>
                        <td>{{ blog.title }}</td>
                        <td>{{ blog.blog_cate.name }}</td>
                        <td>
                          <img
                            width="70"
                            :src="`/storage/${blog.image}`"
                            alt=""
                          />
                        </td>
                        <td>
                          <a
                            class="btn btn-primary"
                            href="javascript:void(0);"
                            v-on:click="updateForm(blog.id)"
                            ><font-awesome-icon
                              :icon="['fas', 'pen-to-square']"
                          /></a>
                          <a
                            class="btn btn-primary"
                            href="javascript:void(0);"
                            v-on:click="deleteBblog(blog.id)"
                            ><font-awesome-icon :icon="['fas', 'trash-can']"
                          /></a>
                          <router-link
                            class="btn btn-primary"
                            :to="{
                              name: 'details',
                              params: { id: blog.id },
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
import AddBlog from "./AddBlog.vue";
import EditBlog from "./EditBlog.vue";
export default {
  name: "blog",
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
    AddBlog,
    EditBlog,
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
  methods: {
    selectCate(page) {
      if (typeof page === "undefined") {
        page = 1;
      }
      axios
        .get(
          "/api/blogs?page=" +
            page +
            "&category=" +
            this.filter.cate +
            "&order=" +
            this.filter.order +
            "&search=" +
            this.filter.keyword
        )
        .then((response) => {
          console.log(response.data.data);
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
        .get("/api/blogs/updateForm/" + id)
        .then((response) => {
          this.isEdit = "OpenEdit";
          this.updateItem = response.data;
          console.log(response.data);
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
.btn-primary{
  margin-top: 3px;
}
</style>