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
              id="name"
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
import Slug from "../../lib/slug";
export default {
  name: "EditCategory",
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
    };
  },
  created() {
    this.errors = new FormErrors();
  },
  computed: {
    slugTitle: function () {
      var slug = Slug.sanitizeTitle(this.updateItem.name);
      return slug;
    },
  },
  methods: {
    Cancel() {
      this.$emit("handleEditForm");
    },
    editCate(id, e) {
      var formData = new FormData($("#formCategory")[0]);
      formData.append("slug",this.slugTitle)
      axios
        .post("/api/categories/update/" + id, formData)
        .then((response) => {
          console.log(response);
          if (response.status == 200) {
            this.$emit("handleEditForm");
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