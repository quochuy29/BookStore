<template>
  <section class="cart-details">
    <h2 class="heading">Thanh toán</h2>
    <div class="cart-detail-container">
      <div class="group-double">
        <form
          action="javascript:void(0);"
          method="POST"
          @submit="checkOut($event)"
          id="checkOut"
          enctype="multipart/form-data"
        >
          <div class="group-item">
            <div class="cart-detail-heading">
              <span>Thông tin liên lạc</span>
            </div>
            <div class="form-group">
              <label for="" class="group-label"
                >Số điện thoại <span class="text-red">*</span></label
              >
              <input
                type="text"
                name="phone"
                placeholder="Số điện thoại"
                value=""
              />
            </div>
            <div class="form-group">
              <label for="" class="group-label"
                >Địa chỉ email <span class="text-red">*</span></label
              >
              <input type="text" name="email" placeholder="Email" value="" />
            </div>
            <div class="cart-detail-heading">
              <span>Địa chỉ giao hàng</span>
            </div>
            <div class="form-group">
              <label for="" class="group-label"
                >Họ tên <span class="text-red">*</span></label
              >
              <input type="text" placeholder="Họ & Tên" name="name" />
            </div>
            <div class="form-group">
              <label for="" class="group-label"
                >Thành phố <span class="text-red">*</span></label
              >
              <select name="calc_shipping_provinces" required="">
                <option value="">Tỉnh / Thành phố</option>
                <option value="HN">Hà Nội</option>
              </select>
              <input
                class="billing_address_1"
                name="city"
                type="hidden"
                value="HN"
              />
            </div>
            <div class="form-group">
              <label for="" class="group-label"
                >Quận / Huyện <span class="text-red">*</span></label
              >
              <select name="calc_shipping_district" required="">
                <option value="">Quận / Huyện</option>
                <option value="cg">Cầu giấy</option>
              </select>
              <input
                class="billing_address_2"
                name="district"
                type="hidden"
                value="cG"
              />
            </div>
            <div class="form-group">
              <label for="" class="group-label"
                >Phường / Xã <span class="text-red">*</span></label
              >
              <input
                type="text"
                placeholder="Phường xã (Cầu Diễn)"
                name="ward"
                value=""
              />
            </div>
            <div class="form-group">
              <label for="" class="group-label"
                >Địa chỉ <span class="text-red">*</span></label
              >
              <input
                type="text"
                placeholder="Địa chỉ nhà (196 Hồ Tùng Mậu)"
                name="address"
                value=""
              />
            </div>
            <div class="cart-detail-heading">
              <span>Ghi chú khi giao hàng</span>
            </div>
            <div class="form-group">
              <textarea
                name="note"
                id=""
                cols="30"
                rows="10"
                placeholder="Ghi chú"
              ></textarea>
            </div>
            <div class="cart-detail-heading">
              <span>Hình thức thanh toán</span>
            </div>
            <div class="form-group-type-payment">
              <input
                type="radio"
                name="ptvc"
                id="ptvc"
                value="1"
                checked
              /><label for="ptvc">Thanh toán khi giao hàng</label>
            </div>
          </div>
          <input type="hidden" name="txt_bill_country" value="VN">
          <div class="group-item">
            <div class="pay-second">
              <div class="cart-detail-heading">
                <span>Đơn hàng của bạn</span>
              </div>
              <div class="pay-second-container">
                <div class="item">
                  <label for="language">Loại hàng hóa </label>
                  <select
                    name="order_type"
                    id="order_type"
                    class="form-control"
                  >
                    <option value="billpayment">Thanh toán hóa đơn</option>
                  </select>
                </div>
                <div class="item">
                  <label for="bank_code">Ngân hàng</label>
                  <select name="bank_code" id="bank_code" class="form-control">
                    <option value="">Không chọn</option>
                    <option value="NCB">Ngan hang NCB</option>
                    <option value="AGRIBANK">Ngan hang Agribank</option>
                    <option value="SCB">Ngan hang SCB</option>
                    <option value="SACOMBANK">Ngan hang SacomBank</option>
                    <option value="EXIMBANK">Ngan hang EximBank</option>
                    <option value="MSBANK">Ngan hang MSBANK</option>
                    <option value="NAMABANK">Ngan hang NamABank</option>
                    <option value="VNMART">Vi dien tu VnMart</option>
                    <option value="VIETINBANK">Ngan hang Vietinbank</option>
                    <option value="VIETCOMBANK">Ngan hang VCB</option>
                    <option value="HDBANK">Ngan hang HDBank</option>
                    <option value="DONGABANK">Ngan hang Dong A</option>
                    <option value="TPBANK">Ngân hàng TPBank</option>
                    <option value="OJB">Ngân hàng OceanBank</option>
                    <option value="BIDV">Ngân hàng BIDV</option>
                    <option value="TECHCOMBANK">Ngân hàng Techcombank</option>
                    <option value="VPBANK">Ngan hang VPBank</option>
                    <option value="MBBANK">Ngan hang MBBank</option>
                    <option value="ACB">Ngan hang ACB</option>
                    <option value="OCB">Ngan hang OCB</option>
                    <option value="IVB">Ngan hang IVB</option>
                    <option value="VISA">Thanh toan qua VISA/MASTER</option>
                  </select>
                </div>
                <div class="item">
                  <span class="both">Tổng tiền</span>
                  <span class="both total"> {{ total }} đ </span>
                  <input type="hidden" :value="total" name="grand_total" />
                </div>
              </div>
              <div class="item-last">
                <button class="btn-pay" type="submit" id="cart-next">
                  <span>Thanh toán</span>
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>

<script>
import Random from '../../../lib/ramdomKey';
export default {
  name: "CheckOut",
  data() {
    return {
      total: 0,
    };
  },
  created() {
    this.loadTotal();
  },
  methods: {
    loadTotal() {
      axios.get("/api/gio-hang/check-out").then((response) => {
        console.log(response);
        this.total = response.data.total;
      });
    },
    checkOut(event) {
      if(!sessionStorage.getItem('key')){
        sessionStorage.setItem('key',Random.random(10));
      }
      var formData = new FormData($("#checkOut")[0]);
      formData.append('key_orders',sessionStorage.getItem('key'));
      axios.post("/api/gio-hang/pay-ment", formData).then((response) => {
       console.log(response.data);
      });
    },
  },
};
</script>

<style lang="scss" scoped>
</style>