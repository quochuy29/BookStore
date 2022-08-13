<template>
  <div>
    <Page403 v-if="role != 1 && role != 5"></Page403>
    <div class="" v-if="role == 1 || role == 5">
      <Header :key="$route.fullPath"></Header>
      <Sidebar v-bind:permission="permission" v-bind:roles="roles"></Sidebar>
      <div class="content-wrapper">
        <router-view></router-view>
      </div>
      <Footer :key="$route.name"></Footer>
    </div>
  </div>
</template>

<script>
import Header from "./header.vue";
import Sidebar from "./sidebar.vue";
import Footer from "./footer.vue";
import Page403 from "../../errors/403.vue";
import Page404 from "../../errors/404.vue";
import Permission from "../../lib/permission";
export default {
  name: "MainAdmin",
  data() {
    return {
      Menu: true,
      token: localStorage.getItem("token"),
      role: 1,
      permission: [],
      roles: [],
    };
  },
  components: {
    Header,
    Sidebar,
    Footer,
    Page403,
    Page404,
  },
  created() {
    this.checkRole();
    this.checkPermissionRole();
  },
  methods: {
    checkPermissionRole() {
      let data = Permission.permission();
      data.then((response) => {
        this.permission = Permission.hasPermission(response.data.permission);
        this.roles = Permission.harRole(response.data.role);
      });
    },
    checkRole() {
      window.axios.defaults.headers.common[
        "Authorization"
      ] = `Bearer ${this.token}`;
      axios.get("/api/auth/checkRole").then((response) => {
        if (response.data.role_id > 0) {
          this.role = response.data.role_id;
        }
      });
    },
  },
};
</script>

<style lang="less" scoped>
</style>