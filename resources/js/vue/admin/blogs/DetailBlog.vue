<template>
  <div class="content">
    <div class="title">
      {{ blogDetail.title }}
    </div>
    <div class="datetime">
      Ngày đăng : {{ blogDetail.updated_at | moment("h:mm L") }}
    </div>
    <div v-html="blogDetail.description" class="content-title"></div>
    <div v-if="blogDetail.user" class="author text-right">
      Tác giả : {{ blogDetail.user.name }}
    </div>
  </div>
</template>

<script>
export default {
  name: "DetailBlog",
  props: {
    id: {
      type: String,
    },
  },
  data() {
    return {
      blogDetail: {},
      loading: false,
    };
  },
  mounted() {
    this.loading = false;
    axios.get("/api/blogs/detail/" + this.id).then((response) => {
      this.loading = true;
      this.blogDetail = response.data;
      console.log(this.blogDetail);
    });
  },
};
</script>

<style lang="scss" scoped>
</style>