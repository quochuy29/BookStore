<template>
  <div class="container-lg" id="repair-form">
    <div class="background"></div>
    <div class="edit-form">
      <form
        action="javascript:void(0);"
        @submit.prevent="editCate(updateItem.id, $event)"
        method="post"
        class="form-category"
        enctype="multipart/form-data"
        id="formCategory"
      >
        <h1 class="title">Chỉnh sửa danh mục</h1>
        <div class="form-row">
          <div class="form-group col-md-6 input-category">
            <label for="">Tên danh mục</label>
            <input
              type="text"
              class="form-control"
              name="name"
              v-model="updateItem.name"
            />
            <div
              class="alert alert-danger mt-1"
              :class="{ 'has-error': errors.has('name') }"
            >
              {{ errors.first("name") }}
            </div>
          </div>
          <div class="form-group col-md-6 input-category">
            <div class="form-group">
              <label for="">Trạng thái</label>
              <div class="form-control">
                <label class="pr-1">
                  <input
                    type="radio"
                    name="status"
                    value="1"
                    v-model="updateItem.status"
                  />
                  Active
                </label>
                <label class="pl-1">
                  <input
                    type="radio"
                    name="status"
                    v-model="updateItem.status"
                    value="0"
                  />
                  Inactive
                </label>
              </div>
              <div
                class="alert alert-danger mt-1"
                :class="{ 'has-error': errors.has('status') }"
              >
                {{ errors.first("status") }}
              </div>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="col-12">
            <vue-editor
              name="detail"
              @image-added="handleImageAdded"
              id="detail"
              v-model="updateItem.detail"
            ></vue-editor>
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
import { VueEditor, Quill } from "vue2-editor";
export default {
  name: "EditGenres",
  props: {
    updateItem: {
      type: Object,
      default: {},
    },
  },
  data() {
    return {
      errors: null,
      myModal: false,
    };
  },
  components: {
    VueEditor,
  },
  created() {
    this.errors = new FormErrors();
  },
  mounted() {},
  methods: {
    handleImageAdded: function (file, Editor, cursorLocation, resetUploader) {
      var formData = new FormData();
      formData.append("image", file);

      axios({
        url: "/api/upload",
        method: "POST",
        data: formData,
      })
        .then((result) => {
          const url = result.data.url;
          Editor.insertEmbed(cursorLocation, "image", url);
          resetUploader();
        })
        .catch((err) => {
          console.log(err);
        });
    },
    Cancel() {
      this.$emit("handleEditForm");
    },
    editCate(id, e) {
      var formData = new FormData($("#formCategory")[0]);
      formData.set("detail", this.updateItem.detail);
      axios
        .post("/api/genres/update/" + id, formData)
        .then((response) => {
          console.log(response);
          if (response.status == 200) {
            this.$emit("handleEditForm");
            e.target.reset();
          }
        })
        .catch((error) => {
          console.log(error);
          if (error.response.status == 422) {
            this.errors.setMessages(error.response.data.error);
          }
        });
    },
  },
};
</script>

<style scoped>
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
.form-category {
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

.input-category {
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
</style>