<template>
  <div class="container-lg" id="add-form">
    <div class="background"></div>
    <div class="create-form">
      <form
        action="javascript:void(0);"
        @submit="addBlog($event)"
        method="post"
        class="form-blog"
        enctype="multipart/form-data"
      >
        <h1 class="title">Tạo bài viết</h1>
        <div class="form-row">
          <div class="form-group col-md-6 input-product">
            <label for="inputEmail4">Tiêu đề</label>
            <input
              type="text"
              class="form-control input-border"
              id="title"
              name="title"
            />
            <div
              class="text-danger mt-1"
              :class="{ 'has-error': errors.has('title') }"
            >
              {{ errors.first("title") }}
            </div>
          </div>
          <div class="form-group col-md-6 input-product">
            <label for="">Danh mục bài viết</label>
            <Multiselect
              v-model="blogCate_id"
              :options="category"
              :searchable="true"
              track-by="id"
              label="name"
            >
            </Multiselect>
            <div
              class="text-danger mt-1"
              :class="{ 'has-error': errors.has('blogCate_id') }"
            >
              {{ errors.first("blogCate_id") }}
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6 input-product">
            <label for="">Tag bài viết</label>
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
        </div>
        <div class="form-row">
          <label for="">Nội dung</label>
          <div class="col-12">
            <vue-editor
              name="description"
              @image-added="handleImageAdded"
              id="description"
              v-model="description"
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
import Slug from "../../lib/slug";
export default {
  name: "AddBlog",
  data() {
    return {
      category: [],
      newImage: "",
      errors: null,
      blogCate_id: "",
      description: "",
      value: [],
      options: [],
      tag: [],
      token: localStorage.getItem("token"),
    };
  },
  components: {
    Multiselect,
    VueEditor,
  },
  computed: {
    slugTitle: function () {
      var slug = Slug.sanitizeTitle($("#title").val());
      return slug;
    },
  },
  created() {
    this.errors = new FormErrors();

    axios
      .get("/api/blog-categories")
      .then((response) => {
        this.category = response.data.data;
      })
      .catch((error) => {
        console.log(error);
      });

    axios
      .get("/api/tag")
      .then((response) => {
        this.options = response.data.data;
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
    previewFiles(event) {
      const file = event.target.files[0];

      const theReader = new FileReader();
      theReader.onloadend = async () => {
        this.newImage = await theReader.result;
      };
      theReader.readAsDataURL(file);
    },
    addBlog(e) {
      var formData = new FormData($(".form-blog")[0]);
      this.value.forEach((el) => {
        this.tag.push(el.id);
      });
      formData.append("slug",this.slugTitle)
      formData.append("tag", this.tag);
      formData.append(
        "blogCate_id",
        this.blogCate_id.id != undefined ? this.blogCate_id.id : ''
      );
      formData.set("description", this.description.toString());
      window.axios.defaults.headers.common[
        "Authorization"
      ] = `Bearer ${this.token}`;
      axios
        .post("/api/blogs/store", formData)
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
.form-blog {
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