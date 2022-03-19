<template>
  <div>
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"
            ><i class="fas fa-bars"></i
          ></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <router-link to="/login" class="nav-link">Home</router-link>
        </li>
        <li class="nav-item d-none d-sm-inline-block" v-if="IsLogin == false">
          <router-link to="/login" class="nav-link">Login</router-link>
        </li>
        <li class="nav-item d-none d-sm-inline-block" v-if="IsLogin == false">
          <router-link to="/register" class="nav-link">Register</router-link>
        </li>
        <li class="nav-item d-none d-sm-inline-block" v-if="IsLogin">
          <router-link to="/profile" class="nav-link">{{
            currentUser.name
          }}</router-link>
        </li>
        <li class="nav-item d-none d-sm-inline-block" v-if="IsLogin">
          <a href="javascript:void(0);" @click="Logout" class="nav-link"
            >Logout</a
          >
        </li>
      </ul>
      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
export default {
  name: "Header",
  data() {
    return {
      currentUser: {},
      token: localStorage.getItem("token"),
      IsLogin: true,
    };
  },
  methods: {
    Logout() {
      axios.get("/api/auth/logout").then((response) => {
        localStorage.removeItem("token");
        this.$router.push("/login");
        this.IsLogin = false;
      });
    },
    isLogin() {
      if (this.token) {
        this.IsLogin = true;
      } else {
        this.IsLogin = false;
      }
    },
  },
  mounted() {
    this.isLogin();
    if (this.token) {
      window.axios.defaults.headers.common[
        "Authorization"
      ] = `Bearer ${this.token}`;
      axios.get("/api/user").then((response) => {
        console.log(response);
        this.currentUser = response.data;
      });
    }
  },
};
</script>

<style lang="css" scoped>
</style>