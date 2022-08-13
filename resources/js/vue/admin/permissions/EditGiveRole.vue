<template>
  <div class="container-lg" id="repair-form">
    <div class="background"></div>
    <div class="edit-form">
      <form
        action="javascript:void(0);"
        @submit="updateUserRole(updateItem.id)"
        method="post"
        class="form-userRole"
        id="edit-userRole"
        enctype="multipart/form-data"
      >
        <h1 class="title">Chỉnh sửa quyền</h1>
        <div class="form-group col-md-6 input-product">
          <label for="inputEmail4">Tên vai trò</label>
          <input
            type="text"
            class="form-control input-border"
            disabled
            id="name"
            name="name"
            v-model="updateItem.nameUser"
          />
        </div>
        <div class="form-group col-md-6 input-product">
          <label for="inputEmail4">Tên quyền</label>
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
  name: "EditGiveRole",
  props: {
    updateItem: {
      type: Object,
      default: {},
    },
  },
  data() {
    return {
      errors: null,
      role: {},
      roles: [],
      nameColumn: [],
    };
  },
  components: {
    Multiselect,
  },
  created() {
    this.errors = new FormErrors();
    this.loadUser();
  },
  methods: {
    loadUser() {
      axios
        .get("/api/role/get-roleUser")
        .then((response) => {
          this.roles = response.data.role;
          this.roles.forEach((el) => {
            if (el.id == this.updateItem.idRole) {
              this.role = el;
            }
          });
        })
        .catch((err) => {});
    },
    updateUserRole(id) {
      var formData = new FormData($("#edit-userRole")[0]);
      formData.append("role_id", this.role.id);
      axios
        .post("/api/role/update-give-role/" + id, formData)
        .then((response) => {
          if (response.status == 200) {
            this.$toaster.success(response.data.status);
            this.$emit("handleEditGiveRole");
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
    Cancel() {
      this.$emit("handleEditGiveRole");
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
.form-userRole {
  background-color: #fff;
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
  padding: 5px;
  text-align: center;
}
</style>