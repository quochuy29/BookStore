<template>
  <div>
    <Header :key="$route.fullPath"></Header>
    <Sidebar></Sidebar>
    <div class="content-wrapper">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import Header from "./header.vue";
import Sidebar from "./sidebar.vue";
export default {
  name: "MainAdmin",
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