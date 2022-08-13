<template>
  <div class="container-lg" id="repair-form">
    <div class="background"></div>
    <div class="edit-form">
      <form
        action="javascript:void(0);"
        @submit="EditUser(updateItem.id)"
        method="post"
        class="form-user"
        enctype="multipart/form-data"
        id="formUser"
      >
        <h1 class="title">Tạo người dùng</h1>
        <div class="form-row">
          <div class="form-group col-md-6 input-user">
            <label for="inputEmail4">Tên người dùng</label>
            <input
              type="text"
              class="form-control input-border"
              v-model="updateItem.name"
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
              v-model="updateItem.email"
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
            <div class="image" v-if="newImage == ''">
              <img
                :src="`/storage/${this.updateItem.avatar}`"
                class="img-thumbnail mt-3"
              />
            </div>
            <div class="image" v-if="newImage">
              <img :src="newImage" class="img-thumbnail mt-3" />
            </div>
            <div
              class="alert alert-danger mt-1"
              :class="{ 'has-error': errors.has('avatar') }"
            >
              {{ errors.first("avatar") }}
            </div>
          </div>
          <div class="form-group col-md-6 input-user">
            <label for="">Số điện thoại</label>
            <input type="text" name="phone_number" v-model="updateItem.phone_number" class="form-control" />
            <div
              class="text-danger mt-1"
              :class="{ 'has-error': errors.has('phone_number') }"
            >
              {{ errors.first("phone_number") }}
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
  name: "EditUser",
  props: {
    updateItem: {
      type: Object,
      default: {},
    },
  },
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
      role:[]
    };
  },
  components: {
    Multiselect,
  },
  created() {
    this.errors = new FormErrors();
    this.checkPermissionRole();
    this.loadRole();
  },
  methods: {
    checkPermissionRole() {
      let data = Permission.permission();
      data.then((response) => {
        this.per = Permission.hasPermission(response.data.permission);
        this.roles = Permission.harRole(response.data.role);
      });
    },
    loadRole() {
      axios
        .get("/api/role/get-roleUser")
        .then((response) => {
          this.role = response.data.role;
          this.role.forEach((el) => {
            if (el.id == this.updateItem.idRole) {
              this.hasRole = el;
            }
          });
        })
        .catch((err) => {});
    },
    EditUser(id){

    },
    previewFiles(event) {
      const file = event.target.files[0];

      const theReader = new FileReader();
      theReader.onloadend = async () => {
        this.newImage = await theReader.result;
      };
      theReader.readAsDataURL(file);
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

.container-lg.edit {
  position: fixed;
  transition: all 0.3s ease;
  z-index: 9999;
  opacity: 1;
  visibility: visible;
}

.edit .edit-form {
  transform: scale(1);
}

.edit-form {
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
  padding: 5px;
  text-align: center;
}

.remove {
  text-align: center;
}
</style>