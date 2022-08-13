<template>
  <div>
    <header-store :key="$route.fullPath"></header-store>
    <div @click="removeSearch">
      <router-view></router-view>
    </div>
    <footer-store @click="removeSearch" :key="$route.name"></footer-store>
  </div>
</template>

<script>
import HeaderStore from "./header.vue";
import FooterStore from "./footer.vue";
export default {
  name: "MainStore",
  props: {
    name: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      check404: 0,
    };
  },
  components: {
    HeaderStore,
    FooterStore,
  },
  methods: {
    removeSearch() {
      this.$root.$emit("removeSearch", false);
    },
  },
};
</script>

<style lang="less" scoped>
</style>