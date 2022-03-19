<template>
  <div>
    <section class="footer">
      <div class="box-container">
        <div class="box">
          <h3>Our Locations</h3>
          <a href="#"> <i class="fas fa-map-marker-alt"></i> India </a>
          <a href="#"> <i class="fas fa-map-marker-alt"></i> USA </a>
          <a href="#"> <i class="fas fa-map-marker-alt"></i> Russia </a>
          <a href="#"> <i class="fas fa-map-marker-alt"></i> France </a>
          <a href="#"> <i class="fas fa-map-marker-alt"></i> Japan </a>
          <a href="#"> <i class="fas fa-map-marker-alt"></i> Africa </a>
        </div>

        <div class="box">
          <h3>Quick Links</h3>
          <a href="#"> <i class="fas fa-arrow-right"></i> Home </a>
          <a href="#"> <i class="fas fa-arrow-right"></i> Featured </a>
          <a href="#"> <i class="fas fa-arrow-right"></i> Arrivals </a>
          <a href="#"> <i class="fas fa-arrow-right"></i> Reviews </a>
          <a href="#"> <i class="fas fa-arrow-right"></i> Blogs </a>
        </div>

        <div class="box">
          <h3>Extra Links</h3>
          <a href="#"> <i class="fas fa-arrow-right"></i> Account info </a>
          <a href="#"> <i class="fas fa-arrow-right"></i> Ordered items </a>
          <a href="#"> <i class="fas fa-arrow-right"></i> Privacy policy </a>
          <a href="#"> <i class="fas fa-arrow-right"></i> Payment method </a>
          <a href="#"> <i class="fas fa-arrow-right"></i> Our serivces </a>
        </div>

        <div class="box">
          <h3>Contact Info</h3>
          <a href="#"> <i class="fas fa-phone"></i> +123-456-7890 </a>
          <a href="#"> <i class="fas fa-phone"></i> +111-222-3333 </a>
          <a href="#"> <i class="fas fa-envelope"></i> shaikhanas@gmail.com </a>
          <img src="image/worldmap.png" class="map" alt="" />
        </div>
      </div>

      <div class="share">
        <a href="#" class="fab fa-facebook-f"></a>
        <a href="#" class="fab fa-twitter"></a>
        <a href="#" class="fab fa-instagram"></a>
        <a href="#" class="fab fa-linkedin"></a>
        <a href="#" class="fab fa-pinterest"></a>
      </div>

      <div class="credit">
        Created by <span>Phan Quốc Huy</span> | All rights reserved!
      </div>
    </section>

    <!-- footer section ends -->

    <!-- loader  -->

    <div class="loader-container" v-if="open">
      <img src="/storage/image/loader-img.gif" alt="" />
    </div>
  </div>
</template>

<script>
export default {
  name: "footerStore",
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
.footer {
  padding: 2.5rem 5rem;
}
.footer .box-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(14rem, 1fr));
  gap: 1.2rem;
}

.footer .box-container .box h3 {
  font-size: 1.4rem;
  color: #444;
  padding: 1rem 0;
}

.footer .box-container .box a {
  display: block;
  font-size: 1rem;
  color: #666;
  padding: 1rem 0;
}

.footer .box-container .box a i {
  color: #27a357;
  padding-right: 0.5rem;
}

.footer .box-container .box a:hover i {
  padding-right: 2rem;
}

.footer .box-container .box .map {
  width: 100%;
}

.footer .share {
  padding: 1rem 0;
  text-align: center;
}

.footer .share a {
  height: 3rem;
  width: 3rem;
  line-height: 3rem;
  font-size: 1rem;
  color: #fff;
  background: #27a;
  margin: 0 0.3rem;
  border-radius: 50%;
}

.footer .share a:hover {
  background: #444;
}

.footer .credit {
  border-top: 0.1rem solid rgba(0, 0, 0, 0.1);
  margin-top: 2rem;
  padding: 0 1rem;
  padding-top: 2.5rem;
  font-size: 1rem;
  color: #666;
  text-align: center;
}

.footer .credit span {
  color: #27a;
}
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