<template>
    <hooper
      :progress="true"
      :wheelControl="false"
      :itemsToShow="1"
      pagination="fraction"
    >
      <slide>
        <div class="slider-image">
          <img src="/storage/image/920X420-100.jpg" alt="" />
        </div>
      </slide>
      <slide>
        <div class="slider-image">
          <img src="/storage/image/bigsale-resize_920.jpg" alt="" />
        </div>
      </slide>
      <slide>
        <div class="slider-image">
          <img src="/storage/image/manga_920_x_420-t3.jpg" alt="" />
        </div>
      </slide>
      <slide>
        <div class="slider-image">
          <img src="/storage/image/920x420.jpg" alt="" />
        </div>
      </slide>
      <hooper-navigation slot="hooper-addons"></hooper-navigation>
      <hooper-pagination slot="hooper-addons"></hooper-pagination>
    </hooper>
</template>

<script>
import {
  Hooper,
  Slide,
  Pagination as HooperPagination,
  Navigation as HooperNavigation,
} from "hooper";
import "hooper/dist/hooper.css";
export default {
  name: "slider",
  components: {
    Hooper,
    Slide,
    HooperPagination,
    HooperNavigation,
  },
};
</script>

<style lang="less" scoped>

  .hooper {
    height: 100%;
    text-align: center;
    img {
      max-width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }

</style>