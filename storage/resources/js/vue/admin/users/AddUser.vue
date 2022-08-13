<template>
  <div class="container-lg" id="add-form">
    <div class="background"></div>
    <div class="create-form">
      <form
        action="javascript:void(0);"
        @submit="AddUser($event)"
        method="post"
        class="form-user"
        enctype="multipart/form-data"
      >
        <h1 class="title">Tạo người dùng</h1>
        <div class="form-row">
          <div class="form-group col-md-6 input-user">
            <label for="inputEmail4">Tên người dùng</label>
            <input
              type="text"
              class="form-control input-border"
              id="name"
              name="name"
            />
            <div
              class="text-danger mt-1"
              :class="{ 'has-error': errors.has('name') }"
            >
              {{ errors.first("name") }}
            </div>
          </div>
          <div class="form-group col-md-6 input-user">
            <label for="inputEmail4">Email</label>
            <input
              type="text"
              class="form-control input-border"
              id="email"
              name="email"
            />
            <div
              class="text-danger mt-1"
              :class="{ 'has-error': errors.has('email') }"
            >
              {{ errors.first("email") }}
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6 input-user">
            <label for="">Ảnh đại diện</label>
            <input
              type="file"
              name="avatar"
              @change="previewFiles($event)"
              class="form-control"
            />
            <img :src="newImage" class="img-thumbnail mt-3" />
            <div
              class="text-danger mt-1"
              :class="{ 'has-error': errors.has('avatar') }"
            >
              {{ errors.first("avatar") }}
            </div>
          </div>
          <div class="form-group col-md-6 input-user">
            <label for="">Số điện thoại</label>
            <input type="text" name="phone_number" class="form-control" />
            <div
              class="text-danger mt-1"
              :class="{ 'has-error': errors.has('phone_number') }"
            >
              {{ errors.first("phone_number") }}
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6 input-user">
            <label for="inputEmail4">Password</label>
            <input
              type="password"
              class="form-control input-border"
              id="password"
              name="password"
            />
            <div
              class="text-danger mt-1"
              :class="{ 'has-error': errors.has('password') }"
            >
              {{ errors.first("password") }}
            </div>
          </div>
          <div class="form-group col-md-6 input-user">
            <label for="inputEmail4">Password confirmation</label>
            <input
              type="password"
              class="form-control input-border"
              id="password_confirmation"
              name="password_confirmation"
            />
            <div
              class="text-danger mt-1"
              :class="{ 'has-error': errors.has('password_confirmation') }"
            >
              {{ errors.first("password_confirmation") }}
            </div>
          </div>
        </div>
        <div class="form-row" v-if="roles.includes('admin')">
          <div class="form-group col-md-12 input-user">
            <label for="">Danh mục vai trò</label>
            <Multiselect
              v-model="hasRole"
              :options="roleUser"
              :searchable="true"
              track-by="id"
              label="name"
            >
            </Multiselect>
            <div
              class="text-danger mt-1"
              :class="{ 'has-error': errors.has('role_id') }"
            >
              {{ errors.first("role_id") }}
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
import FormErrors from "laravel-form-errors";
import Multiselect from "vue-multiselect";
import Permission from "../../lib/permission";
export default {
  name: "AddUser",
  data() {
    return {
      errors: null,
      nameColumn: [],
      hasRole: [],
      newImage: "",
      images: [],
      value: [],
      options: [],
      per: [],
      roles: [],
      roleUser: [],
    };
  },
  components: {
    Multiselect,
  },
  created() {
    this.errors = new FormErrors();
    this.checkPermissionRole();
    this.loadRoleUser();
  },
  methods: {
    loadRoleUser() {
      axios
        .get("/api/role/get-roleUser")
        .then((response) => {
          this.roleUser = response.data.role;
        })
        .catch((err) => {});
    },
    AddUser(e) {
      var formData = new FormData($(".form-user")[0]);

      this.hasRole.id ? formData.append("role_id", this.hasRole.id) : "";
      axios
        .post("/api/users/store", formData)
        .then((response) => {
          if (response.status == 200) {
            this.$toaster.success(response.data.status)
          }
        })
        .catch((error) => {
          this.nameColumn = [];
          if (error.response.status == 422) {
            this.errors.setMessages(error.response.data.error);
            var column = error.response.data.tu;
            for (let key in column) {
              this.nameColumn.push(key);
            }
            let err = "";
            this.nameColumn.forEach((index, el) => {
              if (this.errors.first(index) !== "") {
                err += this.errors.first(index) + "\n\n";
              }
            });
            this.$toaster.error(`${err}`);
          }
        });
    },
    previewFiles(event) {
      const file = event.target.files[0];

      const theReader = new FileReader();
      theReader.onloadend = async () => {
        this.newImage = await theReader.result;
      };
      theReader.readAsDataURL(file);
    },
    checkPermissionRole() {
      let data = Permission.permission();
      data.then((response) => {
        this.per = Permission.hasPermission(response.data.permission);
        this.roles = Permission.harRole(response.data.role);
      });
    },
    Cancel() {
      this.$emit("closeForm");
    },
  },
};
</script>

<style lang="less" scoped>
#upload-photo {
  opacity: 0;
  position: absolute;
  left: 94%;
  z-index: 10003;
}

.cloud-upload {
  z-index: 10000;
  position: relative;
}

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
.form-user {
  background-color: #fff;
  border-radius: 3px;
  overflow-y: scroll;
  overflow-x: hidden;
  height: 640px;
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

.input-user {
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