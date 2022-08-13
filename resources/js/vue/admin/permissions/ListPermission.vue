<template>
  <div class="list-product">
    <div class="content-header">
      <div class="container-fluid pd-1">
        <div class="card card-secondary my-0">
          <div class="card-header">
            <ol class="breadcrumb float-sm-left">
              <li class="breadcrumb-item card-title">Danh sách phân quyền</li>
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
            <add-permission
              :key="addPer"
              v-on:handleAddForm="handleAddForm"
              v-bind:class="addPer == 'OpenCreate' ? 'create' : ''"
            />
            <add-role
              :key="addRoles"
              v-on:handleAddRole="handleAddRole"
              v-bind:class="addRoles == 'OpenRole' ? 'create' : ''"
            />
            <edit-role
              :key="editRoles"
              v-bind:updateItem="updateItem"
              v-on:handleEditRole="handleEditRole"
              v-bind:class="editRoles == 'OpenEdit' ? 'edit' : ''"
            />
            <give-role
              :key="giveRole"
              v-on:handleGiveRole="handleGiveRole"
              v-bind:class="giveRole == 'OpenGive' ? 'create' : ''"
            />
            <edit-give-role
              :key="editGiveRole"
              v-bind:updateItem="updateItem"
              v-on:handleEditGiveRole="handleEditGiveRole"
              v-bind:class="editGiveRole == 'OpenEditGive' ? 'edit' : ''"
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
                        <th scope="col">Vai trò</th>
                        <th scope="col">
                          <button
                            v-if="
                              per.includes('view product') ||
                              roles.includes('admin')
                            "
                            type="button"
                            @click="addRole"
                            class="btn btn-success"
                          >
                            <font-awesome-icon :icon="['fas', 'square-plus']" />
                          </button>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(roles, index) in role" :key="index">
                        <td>{{ index }}</td>
                        <td>
                          {{ roles.nameUser }}
                        </td>
                        <td>
                          {{ roles.nameRole }}
                        </td>
                        <td>
                          <a
                            class="btn btn-primary"
                            @click="editUserRole(roles.id)"
                            href="javascript:void(0);"
                            ><font-awesome-icon
                              :icon="['fas', 'pen-to-square']"
                          /></a>
                          <a
                            class="btn btn-primary"
                            @click="deleteUserRole(roles.id)"
                            href="javascript:void(0);"
                            ><font-awesome-icon :icon="['fas', 'trash-can']"
                          /></a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <table
                    class="table table-bordered data-table"
                    style="width: 100%"
                  >
                    <thead>
                      <tr>
                        <th scope="col">STT</th>
                        <th scope="col">Tên vai trò</th>
                        <th scope="col">Quyền</th>
                        <th scope="col">
                          <button
                            type="button"
                            @click="addRolePermission"
                            class="btn btn-success"
                          >
                            <font-awesome-icon :icon="['fas', 'square-plus']" />
                          </button>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(permissions, index) in permission"
                        :key="index"
                      >
                        <td>{{ index }}</td>
                        <td>
                          {{ permissions.name }}
                        </td>
                        <td>
                          <span
                            class="badge bg-primary ml-1"
                            v-for="(per, index) in permissions.permission"
                            :key="index"
                            >{{ per.name }}</span
                          >
                        </td>
                        <td>
                          <a
                            class="btn btn-primary"
                            @click="editRole(permissions.id)"
                            href="javascript:void(0);"
                            ><font-awesome-icon
                              :icon="['fas', 'pen-to-square']"
                          /></a>
                          <a
                            class="btn btn-primary"
                            @click="deleteRole(permissions.id)"
                            href="javascript:void(0);"
                            ><font-awesome-icon :icon="['fas', 'trash-can']"
                          /></a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <table
                    class="table table-bordered data-table"
                    style="width: 100%"
                  >
                    <thead>
                      <tr>
                        <th scope="col">STT</th>
                        <th scope="col">Tên quyền</th>
                        <th scope="col">
                          <a @click="addPermission()" class="btn btn-success"
                            ><font-awesome-icon :icon="['fas', 'square-plus']"
                          /></a>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(per, index) in permissions" :key="index">
                        <td>{{ index }}</td>
                        <td>
                          <p v-if="editPer !== per.id">{{ per.name }}</p>
                          <div v-if="editPer == per.id" class="edit-permission">
                            <input
                              type="text"
                              class="input-control"
                              :id="`namePer${index}`"
                              :value="`${per.name}`"
                            />
                            <button
                              class="btn btn-success"
                              @click="permissionEdit(per.id, `namePer${index}`)"
                            >
                              Sửa
                            </button>
                          </div>
                        </td>
                        <td>
                          <a
                            class="btn btn-primary"
                            @click="editPermission(per.id)"
                            href="javascript:void(0);"
                            ><font-awesome-icon
                              :icon="['fas', 'pen-to-square']"
                          /></a>
                          <a
                            class="btn btn-primary"
                            @click="deletePermission(per.id)"
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
import AddPermission from "./AddPermission.vue";
import EditRole from "./EditRole.vue";
import AddRole from "./AddRole.vue";
import GiveRole from "./GiveRole.vue";
import Permission from "../../lib/permission";
import EditGiveRole from "./EditGiveRole.vue";
export default {
  name: "ListPermission",
  data() {
    return {
      role: [],
      permission: [],
      permissions: [],
      addPer: "CloseCreate",
      editPer: 0,
      editRoles: "CloseEdit",
      addRoles: "CloseRole",
      giveRole: "CloseGive",
      editGiveRole: "CloseEditGive",
      updateItem: {},
      per: [],
      roles: [],
    };
  },
  components: {
    AddPermission,
    EditRole,
    AddRole,
    GiveRole,
    EditGiveRole,
  },
  created() {
    this.checkPermissionRole();
    this.loadPermission();
    this.listPermission();
  },
  methods: {
    checkPermissionRole() {
      let data = Permission.permission();
      data.then((response) => {
        this.per = Permission.hasPermission(response.data.permission);
        this.roles = Permission.harRole(response.data.role);
        console.log(this.per, this.roles);
      });
    },
    editPermission(id) {
      this.editPer != id ? (this.editPer = id) : (this.editPer = !id);
    },
    permissionEdit(id, idInput) {
      var formData = new FormData();
      formData.append("name", $(`#${idInput}`).val());
      axios
        .post("/api/permission/edit-permission/" + id, formData)
        .then((response) => {
          console.log(response);
          if (response.status == 200) {
            this.listPermission();
            this.editPer = !this.editPer;
            this.loadPermission();
          }
        })
        .catch((error) => {
          console.log(error);
          if (error.response.status == 422) {
            this.errors.setMessages(error.response.data.error);
          }
        });
    },
    listPermission() {
      axios
        .get("/api/permission/get-permission")
        .then((response) => {
          this.permissions = response.data;
        })
        .catch((err) => {});
    },
    loadPermission() {
      axios
        .get("/api/permission")
        .then((response) => {
          this.role = response.data.role;
          this.permission = response.data.permission;
        })
        .catch((err) => {});
    },
    addPermission() {
      this.addPer = "OpenCreate";
    },
    addRolePermission() {
      this.addRoles = "OpenRole";
    },
    closeForm() {
      this.addPer = "CloseCreate";
    },
    handleAddForm() {
      this.addPer = "CloseCreate";
    },
    handleAddRole() {
      this.addRoles = "CloseRole";
    },
    handleEditRole() {
      this.editRoles = "CloseEdit";
      this.loadPermission();
    },
    deleteUserRole(id) {
      axios
        .delete("/api/role/delete/" + id)
        .then((response) => {
          this.loadPermission();
          this.listPermission();
        })
        .catch((err) => {});
    },
    deletePermission(id) {
      axios
        .delete("/api/permission/delete-permission/" + id)
        .then((response) => {
          this.listPermission();
        })
        .catch((err) => {});
    },
    editRole(id) {
      axios
        .get("/api/role/edit-role/" + id)
        .then((response) => {
          this.editRoles = "OpenEdit";
          console.log(response.data);
          this.updateItem = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    editUserRole(id) {
      axios
        .get("/api/role/edit-give-role/" + id)
        .then((response) => {
          this.editGiveRole = "OpenEditGive";
          console.log(response.data);
          this.updateItem = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    addRole() {
      this.giveRole = "OpenGive";
    },
    handleGiveRole() {
      this.giveRole = "CloseGive";
      this.loadPermission();
      this.listPermission();
    },
    handleEditGiveRole() {
      this.editGiveRole = "CloseEditGive";
      this.loadPermission();
      this.listPermission();
    },
    deleteRole(id) {
      console.log(id);
      axios
        .delete("/api/role/delete-role/" + id)
        .then((response) => {
          this.loadPermission();
          this.listPermission();
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>

<style lang="less" scoped>
.insert-permission {
  .col-2 {
    cursor: pointer;
    padding: 10px;
    margin-left: 12px;
    border: 1px solid transparent;
    background: #8888;
    text-align: center;
    color: #fff;
    &:hover {
      background: rgba(255, 88, 0, 0.8);
    }
  }
}

.input-control {
  height: 38px;
  border-radius: 5px;
  border: 1px solid #ced4da;
}

td,
th {
  .btn-success {
    background: #28a745;
  }
}
</style>