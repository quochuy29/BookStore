<template>
  <div class="container-lg" id="add-form">
    <div class="background"></div>
    <div class="create-form">
      <form
        action="javascript:void(0);"
        @submit="addPermission($event)"
        method="post"
        class="form-permission"
        enctype="multipart/form-data"
      >
        <h1 class="title">Tạo quyền</h1>
        <div class="form-group col-md-6 input-product">
          <label for="inputEmail4">Tên quyền</label>
          <input
            type="text"
            class="form-control input-border"
            id="name"
            name="name"
          />
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
export default {
  name: "AddPermission",
  data() {
    return {
       errors: null,
       nameColumn: []
    }
  },
  created() {
    this.errors = new FormErrors();
  },
  methods: {
    addPermission() {
      var formData = new FormData($("form")[0]);
      axios
        .post("/api/permission/add-permission", formData)
        .then((response) => {
          if (response.status == 200) {
            this.$emit("handleAddForm");
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
    Cancel() {
      this.$emit("handleAddForm");
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
.form-permission {
  background-color: #fff;
  border-radius: 3px;
  overflow-y: scroll;
  overflow-x: hidden;
  height: 200px;
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