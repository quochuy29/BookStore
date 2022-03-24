<template>
  <div>
    <div class="row justify-content-center">
      <div class="col-md-10">
        <div class="card">
          <div class="card-header">Register</div>
          <div class="card-body">
            <form
              action="javascript:void(0);"
              @submit="register($event)"
              method="post"
              class="register"
              enctype="multipart/form-data"
            >
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="name">Name</label>
                  <input type="text" class="form-control" name="name" />
                  <p class="text-danger" v-text="errors.name"></p>
                </div>
                <div class="form-group col-md-6">
                  <label for="email">Email</label>
                  <input type="text" class="form-control" name="email" />
                  <p class="text-danger" v-text="errors.email"></p>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" name="password" />
                  <p class="text-danger" v-text="errors.password"></p>
                </div>
                <div class="form-group col-md-6">
                  <label for="password_confirmation">Confirm Password</label>
                  <input
                    type="password"
                    class="form-control"
                    name="password_confirmation"
                  />
                  <p
                    class="text-danger"
                    v-text="errors.password_confirmation"
                  ></p>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="">Phone Number</label>
                  <input type="text" class="form-control" name="phone_number" />
                  <p class="text-danger" v-text="errors.phone_number"></p>
                </div>
                <div class="form-group col-md-6">
                  <label for="">Avatar</label>
                  <input
                    type="file"
                    name="avatar"
                    @change="previewFiles($event)"
                    class="form-control"
                  />
                  <img :src="newImage" class="img-thumbnail mt-3" />
                  <p class="text-danger" v-text="errors.avatar"></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                      Register
                    </button>
                  </div>
                </div>
                <div class="col-md-6 text-right">
                  <router-link to="/login"
                    >Already have an account!</router-link
                  >
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import FormErrors from "laravel-form-errors";
export default {
  name: "register",
  data() {
    return {
      nameColumn: [],
      newImage: "",
      errors: {},
    };
  },
  created() {
    this.errors = new FormErrors();
  },
  methods: {
    previewFiles(event) {
      const file = event.target.files[0];

      const theReader = new FileReader();
      theReader.onloadend = async () => {
        this.newImage = await theReader.result;
      };
      theReader.readAsDataURL(file);
    },
    register(e) {
      var formData = new FormData($(".register")[0]);
      axios
        .post("api/auth/register", formData)
        .then((response) => {
          console.log(response.data);
          this.errors = {};
          this.$router.push("/login");
          this.$toaster.success(
            "Account created successfully, now you can login!"
          );
        })
        .catch((error) => {
          this.nameColumn = [];
          if (error.response.status == 422) {
            // this.errors = errors.response.data.error;
            this.errors.setMessages(error.response.data.error);
            var column = error.response.data.tu
            for(let key in column){
              this.nameColumn.push(key)
            }
            console.log(this.nameColumn);
            let err = "";
            this.nameColumn.forEach((index, el) => {
              err += this.errors.first(index) + "\n\n";
            });
            this.$toaster.error(`${err}`);
          }
        });
    },
  },
};
</script>

<style lang="css" scoped>
</style>