<template>
  <div>
    <Slider></Slider>
    <FeaturedBooks />
    <Banner></Banner>
    <OurBlog />
  </div>
</template>

<script>
import Slider from "../layouts/slider.vue";
import FeaturedBooks from "./products/FeaturedBooks.vue";
import Banner from "../layouts/banner.vue";
import OurBlog from "../store/blog/OurBlog.vue";
export default {
  name: "BookStore",
  data() {
    return {};
  },
  components: {
    FeaturedBooks,
    Slider,
    Banner,
    OurBlog,
  },
  mounted() {
    this.loadReturnUrl();
  },
  methods: {
    loadReturnUrl() {
      if (this.$route.query.messageSuccess) {
        this.$toaster.success(this.$route.query.messageSuccess);
        localStorage.setItem("count", 0);
        setTimeout(
          () => this.$root.$emit("myEvent", localStorage.getItem("count")),
          500
        );
      } else if (this.$route.query.messageError) {
        this.$toaster.error(this.$route.query.messageError);
      }
    },
  },
};
</script>

<style lang="scss" scoped>
</style>