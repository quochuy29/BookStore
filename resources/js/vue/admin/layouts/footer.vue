<template>
  <div>
    <div class="loader-container" v-if="open">
      <img src="/storage/image/loader-img.gif" alt="" />
    </div>
  </div>
</template>

<script>
export default {
  name: "footerAdmin",
  data() {
    return {
      open: true,
    };
  },
  mounted() {
    setTimeout(() => {
      this.open = false;
    }, 2000);
  },
};
</script>

<style lang="less" scoped>
.loader-container {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 10000000;
  bottom: 0;
  width: 100%;
  height: 100%;
  background: #f5f7f6;
  display: flex;
  justify-content: center;
  align-items: center;
  img {
    width: 160px;
    height: 145px;
  }
}
</style>