<template>
  <div class="container-lg" id="repair-form">
    <div class="background"></div>
    <div class="edit-form">
      <form
        action="javascript:void(0);"
        @submit.prevent="editCate(updateItem.id)"
        method="post"
        class="form-product"
        enctype="multipart/form-data"
        id="formProduct"
      >
        <h1 class="title">Chỉnh sửa sản phẩm</h1>
        <div class="form-row">
          <div class="form-group col-md-6 input-product">
            <label for="">Tên sản phẩm</label>
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
          <div class="form-group col-md-6 input-product">
            <label for="inputEmail4">Giá sản phẩm</label>
            <input
              type="text"
              class="form-control"
              name="price"
              v-model="updateItem.price"
            />
            <div
              class="alert alert-danger mt-1"
              :class="{ 'has-error': errors.has('price') }"
            >
              {{ errors.first("price") }}
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6 input-product">
            <label for="">Danh mục sản phẩm</label>
            <Multiselect
              v-model="updateItem.cate_id"
              :options="category"
              :searchable="true"
              track-by="id"
              label="name"
            >
            </Multiselect>
            <div
              class="alert alert-danger mt-1"
              :class="{ 'has-error': errors.has('cate_id') }"
            >
              {{ errors.first("cate_id") }}
            </div>
          </div>
          <div class="form-group col-md-6 input-product">
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
            </div>
            <div
              class="alert alert-danger mt-1"
              :class="{ 'has-error': errors.has('status') }"
            >
              {{ errors.first("status") }}
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6 input-product">
            <label for="">Hình ảnh sản phẩm</label>
            <input
              type="file"
              name="image"
              @change="previewFiles($event)"
              class="form-control"
            />
            <div class="image" v-if="newImage == ''">
              <img
                :src="`/storage/${this.updateItem.image}`"
                class="img-thumbnail mt-3"
              />
            </div>
            <div class="image" v-if="newImage">
              <img :src="newImage" class="img-thumbnail mt-3" />
            </div>
            <div
              class="alert alert-danger mt-1"
              :class="{ 'has-error': errors.has('image') }"
            >
              {{ errors.first("image") }}
            </div>
          </div>
          <div class="form-group col-md-6 input-product">
            <label for="">Số lượng</label>
            <input
              type="number"
              v-model="updateItem.quantity"
              name="quantity"
              class="form-control"
            />
            <div
              class="alert alert-danger mt-1"
              :class="{ 'has-error': errors.has('quantity') }"
            >
              {{ errors.first("quantity") }}
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6 input-product">
            <label for="">Thể loại sách</label>
            <Multiselect
              v-model="value"
              :options="options"
              :searchable="true"
              :multiple="true"
              track-by="id"
              label="name"
            >
            </Multiselect>
          </div>
          <div class="form-group col-md-6 input-product">
            <label for="">Tác giả sách</label>
            <Multiselect
              v-model="valueAuthor"
              :options="authors"
              :searchable="true"
              :multiple="true"
              track-by="id"
              label="name"
            >
            </Multiselect>
            <div
              class="text-danger mt-1"
              :class="{ 'has-error': errors.has('cate_id') }"
            >
              {{ errors.first("cate_id") }}
            </div>
          </div>
        </div>
        <div class="form-row">
          <label for="">Thư viện hình ảnh</label>
          <div class="col-12 text-center">
            <div class="col-12 text-right">
              <a href="javascript:void(0);"
                ><font-awesome-icon
                  style="font-size: 30px"
                  :icon="['fas', 'cloud-arrow-up']"
              /></a>
              <input
                type="file"
                ref="ExcelfileUpload"
                name="file[]"
                multiple
                id="upload-photo"
                @change="uploadImage($event, updateItem.id)"
              />
            </div>
          </div>
          <div class="col-12 d-flex" id="add-image">
            <div
              class="col-2 mt-1 mb-1"
              v-for="(url, index) in updateItem.galleries"
              v-bind:key="index"
            >
              <div class="img-content">
                <img :src="`/storage/${url.url_image}`" alt="" />
                <div class="remove mr-4">
                  <a
                    class="cloud-upload"
                    href="javascript:void(0);"
                    @click="removeImage(url.id)"
                    ><font-awesome-icon :icon="['fas', 'trash-can']"
                  /></a>
                </div>
              </div>
            </div>
            <input type="hidden" name="idRemoveImg" value="" />
            <div
              class="col-2 mt-1 mb-1 previewImg"
              v-for="(url, i) in images"
              v-bind:key="i + 'a'"
              :id="i + 'a'"
            >
              <img :src="url" alt="" />
              <div class="remove mr-4">
                <a
                  class="cloud-uploads"
                  href="javascript:void(0);"
                  @click="removeImagePreview(this, i + 'a', i)"
                  ><font-awesome-icon :icon="['fas', 'trash-can']"
                /></a>
              </div>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="col-12">
            <vue-editor
              name="detail"
              v-model="updateItem.detail"
              @image-added="handleImageAdded"
              id="detail"
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
import { VueEditor, Quill } from "vue2-editor";
import FormErrors from "laravel-form-errors";
import Multiselect from "vue-multiselect";
export default {
  name: "EditProduct",
  props: {
    updateItem: {
      type: Object,
      default: {},
    },
  },
  data() {
    return {
      category: [],
      errors: null,
      myModal: false,
      newImage: "",
      images: [],
      idImg: [],
      nameColumn: [],
      value: [],
      options: [],
      genres: [],
      author: [],
      valueAuthor: [],
      authors: [],
    };
  },
  components: {
    VueEditor,
    Multiselect,
  },
  created() {
    this.errors = new FormErrors();

    axios
      .get("/api/categories")
      .then((response) => {
        this.category = response.data;
        response.data.forEach((el) => {
          if (this.updateItem.cate_id == el.id) {
            this.updateItem.cate_id = el;
          }
        });
      })
      .catch((error) => {
        console.log(error);
      });

    axios
      .get("/api/genres")
      .then((response) => {
        this.options = response.data;
        if (this.updateItem.genres_products !== undefined) {
          this.updateItem.genres_products.forEach((el, index) => {
            response.data.forEach((elG) => {
              if (el.genres_id == elG.id) {
                this.value.push(elG);
              }
            });
          });
        }
      })
      .catch((error) => {
        console.log(error);
      });
    axios
      .get("/api/author")
      .then((response) => {
        this.authors = response.data;
        if (this.updateItem.author_products !== undefined) {
          this.updateItem.author_products.forEach((el, index) => {
            response.data.forEach((elG) => {
              if (el.author_id == elG.id) {
                this.valueAuthor.push(elG);
              }
            });
          });
        }
      })
      .catch((error) => {
        console.log(error);
      });
  },
  mounted() {
    this.$nextTick(() => {
      $("#category-edit").select2({
        dropdownParent: $("#repair-form"),
      });
      $("#genresEdit").select2({
        dropdownParent: $("#repair-form"),
        placeholder: "Chọn thể loại",
      });
    });
  },
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
    uploadImage(e, id) {
      $(this.$refs.ExcelfileUpload).click();
      this.images = [];
      let fileList = Array.prototype.slice.call(e.target.files);
      fileList.forEach((f) => {
        let reader = new FileReader();
        let that = this;

        reader.onload = function (e) {
          that.images.push(e.target.result);
        };
        reader.readAsDataURL(f);
      });
    },
    removeImagePreview(el, id, i) {
      let removeIds = $(`[name="idRemoveImg"]`).val();
      removeIds += `${i}|`;
      $(`[name="idRemoveImg"]`).val(removeIds);
      $(`#${id}`).remove();
    },
    removeImage(id) {
      axios
        .delete("/api/gallery/delete/" + id)
        .then((response) => {
          this.updateItem.galleries = response.data;
        })
        .catch((error) => {
          console.log(error);
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
    Cancel() {
      this.$emit("handleEditForm");
    },
    handleCloseModal() {
      this.myModal = false;
    },
    editCate(id) {
      var formData = new FormData($("#formProduct")[0]);

      var files = formData.getAll("file[]");
      var str = $(`[name="idRemoveImg"]`).val();
      var arr = str
        .substring(0, str.length - 1)
        .replace(/\|/g, ",")
        .split(",");

      arr.forEach((el) => {
        files = files.filter((els, i) => {
          return el != i;
        });
      });

      this.value.forEach((el) => {
        this.genres.push(el.id);
      });

      this.valueAuthor.forEach((el) => {
        this.author.push(el.id);
      });

      formData.append("author", this.author);
      formData.append("genres", this.genres);
      formData.append("cate_id", this.updateItem.cate_id.id);
      formData.set("RemoveImg", arr);
      formData.set("detail", this.updateItem.detail);
      axios
        .post("/api/products/update/" + id, formData)
        .then((response) => {
          if (response.status == 200) {
            $(".previewImg").remove();
            this.$emit("handleEditForm");
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

.form-product {
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
  padding: 5px;
  text-align: center;
}

.remove {
  text-align: center;
}
</style>