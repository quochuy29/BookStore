<template>
  <div class="container-lg" id="add-form">
    <div class="background"></div>
    <div class="create-form">
      <form
        action="javascript:void(0);"
        @submit="addCate($event)"
        method="post"
        class="form-product"
        enctype="multipart/form-data"
      >
        <h1 class="title">Tạo sản phẩm</h1>
        <div class="form-row">
          <div class="form-group col-md-6 input-product">
            <label for="inputEmail4">Tên sản phẩm</label>
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
          <div class="form-group col-md-6 input-product">
            <label for="inputEmail4">Giá sản phẩm</label>
            <input
              type="text"
              class="form-control input-border"
              id="inputEmail4"
              name="price"
            />
            <div
              class="text-danger mt-1"
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
              v-model="cate_id"
              :options="category"
              :searchable="true"
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
          <div class="form-group col-md-6 input-product">
            <div class="form-group">
              <label for="">Trạng thái</label>
              <div class="form-control">
                <label class="pr-1">
                  <input type="radio" name="status" value="1" checked /> Active
                </label>
                <label class="pl-1">
                  <input type="radio" name="status" value="0" /> Inactive
                </label>
              </div>
            </div>
            <div
              class="text-danger mt-1"
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
            <img :src="newImage" class="img-thumbnail mt-3" />
            <div
              class="text-danger mt-1"
              :class="{ 'has-error': errors.has('image') }"
            >
              {{ errors.first("image") }}
            </div>
          </div>
          <div class="form-group col-md-6 input-product">
            <label for="">Số lượng</label>
            <input type="number" name="quantity" class="form-control" />
            <div
              class="text-danger mt-1"
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
            <div
              class="text-danger mt-1"
              :class="{ 'has-error': errors.has('cate_id') }"
            >
              {{ errors.first("cate_id") }}
            </div>
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
                @change="uploadImage($event)"
              />
            </div>
          </div>
          <div class="col-12 d-flex" id="add-image">
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
              @image-added="handleImageAdded"
              id="detail"
              v-model="detail"
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
import Slug from '../../lib/slug';
export default {
  name: "AddProduct",
  data() {
    return {
      cate_id: "",
      errors: null,
      category: [],
      genres: [],
      myModal: false,
      nameColumn: [],
      newImage: "",
      images: [],
      detail: "",
      value: [],
      options: [],
      valueAuthor: [],
      authors: [],
      author:[]
    };
  },
  components: {
    Multiselect,
    VueEditor,
  },
  computed: {
    slugProduct: function () {
      var slug = Slug.sanitizeTitle($("#name").val());
      return slug;
    },
  },
  created() {
    this.errors = new FormErrors();

    axios
      .get("/api/categories")
      .then((response) => {
        this.category = response.data;
      })
      .catch((error) => {
        console.log(error);
      });

    axios
      .get("/api/genres")
      .then((response) => {
        this.options = response.data;
      })
      .catch((error) => {
        console.log(error);
      });

    axios
      .get("/api/author")
      .then((response) => {
        this.authors = response.data;
      })
      .catch((error) => {
        console.log(error);
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
    uploadImage(e) {
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
    previewFiles(event) {
      const file = event.target.files[0];

      const theReader = new FileReader();
      theReader.onloadend = async () => {
        this.newImage = await theReader.result;
      };
      theReader.readAsDataURL(file);
    },
    addCate(e) {
      var formData = new FormData($(".form-product")[0]);

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

      this.valueAuthor.forEach(el=>{
        this.author.push(el.id);
      });

      var detail = this.detail.toString();
      formData.append("slug",this.slugProduct);
      formData.append("genres", this.genres);
      formData.append("author",this.author);
      formData.append("cate_id", this.cate_id.id);
      formData.set("detail", detail);
      formData.set("RemoveImg", arr);
      axios
        .post("/api/products/store", formData)
        .then((response) => {
          if (response.status == 200) {
            this.$emit("handleAddForm");
            console.log(e.target);
            e.target.reset();
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
    handleCloseModal() {
      this.myModal = false;
    },
    Cancel() {
      this.$emit("closeForm");
    },
  },
};
</script>

<style lang="css" scoped>
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
  margin: 0 auto;
  text-align: center;
}
</style>