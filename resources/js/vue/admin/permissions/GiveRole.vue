<template>
  <div class="container-lg" id="add-form">
    <div class="background"></div>
    <div class="create-form">
      <form
        action="javascript:void(0);"
        @submit="giveRole($event)"
        method="post"
        class="give-role"
        id="give-role"
        enctype="multipart/form-data"
      >
        <h1 class="title">Chọn vai trò</h1>
        <div class="form-group col-md-6 input-product">
          <label for="inputEmail4">Tên người dùng</label>
          <Multiselect
            v-model="user"
            :options="users"
            :searchable="true"
            track-by="id"
            label="name"
          >
          </Multiselect>
        </div>
        <div class="form-group col-md-6 input-product">
          <label for="">Tên vai trò</label>
          <Multiselect
            v-model="role"
            :options="roles"
            :searchable="true"
            track-by="id"
            label="name"
          >
          </Multiselect>
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
import Multiselect from "vue-multiselect";
import FormErrors from "laravel-form-errors";
export default {
  name: "GiveRole",
  data() {
    return {
      role: [],
      roles: [],
      user: [],
      users: [],
      valueRole: [],
    };
  },
  components: {
    Multiselect,
  },
  created() {
    this.loadUser();
  },
  methods: {
    loadUser() {
      axios
        .get("/api/role/get-roleUser")
        .then((response) => {
          this.users = response.data.user;
          this.roles = response.data.role;
        })
        .catch((err) => {});
    },
    giveRole() {
      var formData = new FormData($("#give-role")[0]);
      formData.append("role_id",this.role.id);
      formData.append("user_id",this.user.id);
      axios
        .post("/api/role/give-role", formData)
        .then((response) => {
            this.$emit("handleGiveRole");
            this.$toaster.success(response.data.status);
        })
        .catch((err) => {});
    },
    Cancel() {
      this.$emit("handleGiveRole");
    },
  },
};
</script>

<style lang="less" scoped>
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
.give-role {
  background-color: #fff;
  border-radius: 3px;
  height: 350px;
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
  margin: 0 auto;
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