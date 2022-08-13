<template>
  <div class="container-lg" id="add-form">
    <div class="background"></div>
    <div class="create-form">
      <form
        action="javascript:void(0);"
        @submit="addBlogCate($event)"
        method="post"
        class="form-blogCate"
        enctype="multipart/form-data"
      >
        <h1 class="title">Thêm danh mục</h1>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputEmail4">Name</label>
            <input type="text" class="form-control" name="name" />
            <div
              class="alert alert-danger mt-1"
              :class="{ 'has-error': errors.has('name') }"
            >
              {{ errors.first("name") }}
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
export default {
  name: "AddBlogCate",
  data() {
    return {
      errors: null,
    };
  },
  created() {
    this.errors = new FormErrors();
  },
  methods: {
    Cancel() {
      this.$emit("handleAddForm");
    },
    addBlogCate(e) {
      var formData = new FormData($(".form-blogCate")[0]);
      axios
        .post("/api/blog-categories/store", formData)
        .then((response) => {
          if (response.status == 200) {
            this.$emit("handleAddForm");
          }
        })
        .catch((error) => {
          if (error.response.status == 422) {
            this.errors.setMessages(error.response.data.error);
          }
        });
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
.form-blogCate {
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