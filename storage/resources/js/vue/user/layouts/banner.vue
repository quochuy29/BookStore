<template>
  <section class="banner" v-scroll-reveal="{delay: 250}">
    <h3 class="text-center">Phan Quốc Huy</h3>
    <p class="text-center">
      Wellcome , my name is Phan Quốc Huy . I'm 20 years old , i come from Hoàng
      Mai , Nghệ An . And my hometown is Nam Định. At the moment , i'm learning
      in Hà Nội and name school is FPT Polytechnic.
    </p>
  </section>
</template>

<script>
export default {
  name:'banner'
};
</script>

<style lang="less" scoped>
.banner {
  background: url(/storage/image/letter-bg.jpg) no-repeat;
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  padding: 9rem 9%;
  h3,
  p {
    font-style: italic;
    color: #fff;
  }
}
</style>