<template>
  <div>
    <header-store :key="$route.fullPath"></header-store>
    <div>
      <router-view></router-view>
    </div>
    <footer-store :key="$route.name"></footer-store>
  </div>
</template>

<script>
import HeaderStore from "./header.vue";
import FooterStore from "./footer.vue";
export default {
  name: "MainStore",
  components: {
    HeaderStore,
    FooterStore,
  }
};
</script>

<style lang="less" scoped>
</style>