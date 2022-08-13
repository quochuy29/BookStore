<template>
  <div class="list-product">
    <div class="content-header">
      <div class="container-fluid pd-1">
        <div class="card card-secondary my-0">
          <div class="card-header">
            <ol class="breadcrumb float-sm-left">
              <li class="breadcrumb-item card-title">Chi tiết sản phẩm</li>
            </ol>
          </div>
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid pb-1">
        <div class="card card-outline">
          <div class="card-body">
            <div class="d-flex">
              <div class="col-8">
                <div class="image-product">
                  <img
                    :src="`/storage/${productDetail.image}`"
                    alt="Tuổi trẻ đáng giá bao nhiêu"
                    class="img-thumbnail"
                  />
                </div>
              </div>
              <div class="col-4">
                <label for=""
                  >Tên sách : <a href="">{{ productDetail.name }}</a></label
                >
                <label for="" class="mt-2"
                  >Giá sách : {{ productDetail.price }} VND</label
                >
                <label for="" class="mt-2"
                  >Trạng thái sách :
                  {{
                    productDetail.status == 1 ? "Còn hàng" : "Hết hàng"
                  }}</label
                >
                <label for="" v-if="loading" class="mt-2"
                  >Danh mục sách : {{ productDetail.categories.name }}</label
                ><br>
                <label for="" class="mt-2">Thư viện hình ảnh</label>
                <div class="img-gallery">
                  <img
                    v-for="(gallery, index) in productDetail.galleries"
                    v-bind:key="index"
                    :src="`/storage/${gallery.url_image}`"
                    class="rounded float-left pl-1 galleries"
                    alt=""
                  />
                </div>
              </div>
            </div>
            <div class="col-12">
              <label for="" class="mt-2">Chi tiết sách </label>
              <div
                v-html="productDetail.detail"
                class="detail-product mt-5"
              ></div>
            </div>
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->
    </section>
  </div>
</template>

<script>
export default {
  name: "DetailProduct",
  props: {
    id: {
      type: Number,
    },
  },
  data() {
    return {
      productDetail: {},
      loading: false,
    };
  },
  mounted() {
    this.loading = false;
    axios.get("/api/products/detail/" + this.id).then((response) => {
      this.loading = true;
      this.productDetail = response.data;
      console.log(this.productDetail);
    });
  },
};
</script>

<style lang="css" scoped>
.card-secondary:not(.card-outline) > .card-header {
  background-color: #fff;
  color: #333;
}

.content {
  padding: 0 0.5rem;
}

.detail-product {
  width: 100%;
}

.galleries {
  width: 64px;
  object-fit: cover;
  height: 64px;
}
</style>