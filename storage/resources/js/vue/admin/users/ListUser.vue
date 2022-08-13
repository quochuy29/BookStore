<template>
  <div class="list-tag">
    <div class="content-header">
      <div class="container-fluid pd-1">
        <div class="card card-secondary my-0">
          <div class="card-header">
            <ol class="breadcrumb float-sm-left">
              <li class="breadcrumb-item card-title">Danh sách người dùng</li>
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
            <add-user
              :key="isCreate"
              v-on:handleAddForm="handleAddForm"
              v-bind:class="isCreate == 'OpenCreate' ? 'create' : ''"
              v-on:closeForm="closeForm"
            />
            <edit-user
              :key="isEdit"
              v-bind:class="isEdit == 'OpenEdit' ? 'edit' : ''"
              v-bind:updateItem="updateItem"
              v-on:handleEditForm="handleEditForm"
            />
            <h1>Danh sách phân quyền</h1>
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
                        <th scope="col">Tên người dùng</th>
                        <th scope="col">Avatar</th>
                        <th scope="col">Email</th>
                        <th scope="col">
                          <button
                            v-if="
                              per.includes('view product') ||
                              roles.includes('admin')
                            "
                            type="button"
                            @click="addUser"
                            class="btn btn-success"
                          >
                            <font-awesome-icon :icon="['fas', 'square-plus']" />
                          </button>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(user, index) in users" :key="index">
                        <td>{{ index }}</td>
                        <td>
                          {{ user.name }}
                        </td>
                        <td>
                          <img
                            width="120"
                            :src="`/storage/${user.avatar}`"
                            alt=""
                          />
                        </td>
                        <td>
                          {{ user.email }}
                        </td>
                        <td>
                          <a
                            class="btn btn-primary"
                            @click="updateUser(user.id)"
                            href="javascript:void(0);"
                            ><font-awesome-icon
                              :icon="['fas', 'pen-to-square']"
                          /></a>
                          <a
                            class="btn btn-primary"
                            @click="deleteUser(user.id)"
                            href="javascript:void(0);"
                            ><font-awesome-icon :icon="['fas', 'trash-can']"
                          /></a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
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
import AddUser from "./AddUser.vue";
import EditUser from "./EditUser.vue";
import Permission from "../../lib/permission";
export default {
  name: "ListUser",
  props: {},
  data() {
    return {
      isCreate: "CloseCreate",
      isEdit: "CloseEdit",
      updateItem: {},
      users: [],
      token: localStorage.getItem("token"),
      per: [],
      roles: [],
    };
  },
  components: {
    AddUser,
    EditUser,
  },
  mounted() {
    this.loadUser();
    this.checkPermissionRole();
  },
  methods: {
    checkPermissionRole() {
      let data = Permission.permission();
      data.then((response) => {
        this.per = Permission.hasPermission(response.data.permission);
        this.roles = Permission.harRole(response.data.role);
      });
    },
    loadUser() {
      window.axios.defaults.headers.common[
        "Authorization"
      ] = `Bearer ${this.token}`;
      axios
        .get("/api/users")
        .then((response) => {
          this.users = response.data;
          console.log(this.users);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    handleAddForm() {
      this.isCreate = "CloseCreate";
      this.loadUser();
    },
    handleEditForm() {},
    closeForm() {
      this.isCreate = "CloseCreate";
    },
    addUser() {
      this.isCreate = "OpenCreate";
    },
    updateUser(id) {
      axios
        .get("/api/users/updateForm/" + id)
        .then((response) => {
          this.isEdit = "OpenEdit";
          this.updateItem = response.data;
          console.log(response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    deleteUser(id) {},
  },
};
</script>

<style lang="less" scoped>
</style>