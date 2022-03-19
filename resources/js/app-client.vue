<template>
  <div class="">
    <router-view></router-view>
  </div>
</template>

<script>
import Header from "./vue/admin/layouts/header.vue";
import Sidebar from "./vue/admin/layouts/sidebar.vue";
export default {
  name: "mainApp",
  data() {
    return {
      Menu: true,
    };
  },
  components: {
    Header,
    Sidebar,
  },
  methods: {},
};
</script>

<style lang="scss" scoped>
</style>