<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Service\OrderService;
use App\Models\CheckOrderPaymentOnline;
use App\Models\Order_detail;
use App\Models\Orders;
use App\Models\Products;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CartController extends Controller
{
    public $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $cart = Cart::getContent();
        return $cart->count();
    }

    public function cart()
    {
        $total = Cart::getSubtotal();
        $total = Cart::getTotal();
        return response()->json(['content' => Cart::getContent(), 'total' => $total]);
    }

    public function addCart($id, Request $request)
    {
        $product = Products::find($id);

        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $request->quantity,
            'attributes' => array(
                'image' => $product->image,
            )
        ]);

        $cartId = Cart::get($id);
        if ($cartId->quantity > $product->quantity) {
            Cart::update(
                $id,
                [
                    'quantity' => [
                        'relative' => false,
                        'value' => $product->quantity
                    ],
                ]
            );
        }
        $cart = Cart::getContent();
        $countCart = $cart->count();
        $total = Cart::getSubtotal();
        $total = Cart::getTotal();

        return response()->json(['total' => $total, 'count' => $countCart]);
    }

    public function editCart($id, Request $request)
    {
        $product = Products::find($id);
        if ($request->quantity > $product->quantity) {
            Cart::update(
                $id,
                [
                    'quantity' => [
                        'relative' => false,
                        'value' => $product->quantity
                    ],
                ]
            );
            $total = Cart::getSubtotal();
            $total = Cart::getTotal();

            return response()->json(['status' => 'V?????t qu?? s??? l?????ng trong kho', 'total' => $total, 'counter' => $product->quantity]);
        }

        Cart::update(
            $id,
            [
                'quantity' => [
                    'relative' => false,
                    'value' => $request->quantity
                ],
            ]
        );
        $cartId = Cart::get($id);
        $total = Cart::getSubtotal();
        $total = Cart::getTotal();
        return response()->json(['status' => 'C???p nh???t s??? l?????ng s???n ph???m th??nh c??ng', 'total' => $total, 'counter' => $cartId->quantity]);
    }

    public function deleteCart($id)
    {
        Cart::remove($id);
        $cart = Cart::getContent();
        $countCart = $cart->count();
        $total = Cart::getSubtotal();
        $total = Cart::getTotal();
        return response()->json(['status' => 'Xo?? s???n ph???m trong gi??? h??ng th??nh c??ng', 'total' => $total, 'counter' => $countCart]);
    }

    public function clearCart()
    {
        Cart::clear();
        $cart = Cart::getContent();
        $countCart = $cart->count();
        $total = Cart::getSubtotal();
        $total = Cart::getTotal();
        return response()->json(['status' => 'Xo?? t???t c??? s???n ph???m trong gi??? h??ng th??nh c??ng', 'total' => $total, 'counter' => $countCart]);
    }

    public function clearCheck(Request $request)
    {
        $lstIds = explode(',', $request->idCart);
        if ($request->idCart) {
            foreach ($lstIds as $item) {
                Cart::remove($item);
            }
            $cart = Cart::getContent();
            $countCart = $cart->count();
            return response()->json(['status' => 'Xo?? nh???ng s???n ph???m ???? ch???n trong gi??? h??ng th??nh c??ng', 'counter' => $countCart]);
        }
    }

    public function order()
    {
        $cart = Cart::getContent();
        $countCart = $cart->count();
        $total = Cart::getSubtotal();
        $total = Cart::getTotal();
        return response()->json(['total' => $total]);
    }

    public function payment(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'phone' => 'required',
                'email' => 'required',
                'city' => 'required',
                'district' => 'required',
                'ward' => 'required',
                'address' => 'required',
            ],
            [
                'name.required' => "H??y nh???p v??o t??n",
                'phone.required' => "H??y nh???p s??? ??i???n tho???i",
                'email.required' => "Nh???p v??o email ????? theo d??i ????n h??ng c???a b???n",
                'city.required' => "Nh???p v??o th??ng tin Th??nh Ph???",
                'district.required' => "Nh???p v??o th??ng tin Qu???n \ Huy???n",
                'ward.required' => "Nh???p v??o th??ng tin X?? \ Ph?????ng",
                'address.required' => "Nh???p v??o th??ng tin ?????a ch???",
            ]
        );

        $model = Orders::where('key_orders', $request->key_orders)->first();
        if ($model) {
            $order  = Orders::find($model->id);

            $order->fill($request->all());
            if (Auth::check()) {
                $order->user_id = Auth::user()->id;
            }
            $order->shipping_address = $request->address . ", " . $request->ward . ", " . $request->district . ", " . $request->city;
            $order->payment_type = "Thanh to??n online";
            $order->payment_status = 1;
            $order->delivery_status = 1;
            $order->grand_total = $request->grand_total;
            $order->code = date('dmY-His');
            $order->key_orders = $request->key_orders;
            $order->save();

            $id_order = $order->id;
            $content = Cart::getContent();
            // dd($content, $request);

            foreach ($content as $key => $value) {
                $orderDetail = new Order_detail();

                $orderDetail->order_id = $id_order;
                $orderDetail->product_id = $value->id;
                $orderDetail->price = $value->price;

                $orderDetail->shipping_price = 0;

                $orderDetail->payment_status = "Ch??a thanh to??n";
                $orderDetail->delivery_status = "??ang ch??? x??? l??";

                $orderDetail->quantity = $value->quantity;
                $orderDetail->save();
            }
        } else {
            $this->orderService->createOrder($request->all(),$request);
        }
        $vnp_TmnCode = "OSUAQ6IQ";
        $vnp_HashSecret = "NBRJFCCGIDPPFRDLBFOQMQXWRWRANYVU";
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('returnUrl', ['code' => $id_order, 'tax' => 90000]);

        $vnp_TxnRef = $id_order;
        if ($request->note == '') {
            $vnp_OrderInfo = 'Kh??ng c?? ghi ch??';
        } else {
            $vnp_OrderInfo = $request->note;
        }
        $vnp_OrderType = $request->order_type;
        $vnp_Amount = str_replace(',', '', $request->grand_total) * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = $request->bank_code;
        $vnp_IpAddr = $request->ip();
        //Add Params of 2.0.1 Version

        $startTime = date("YmdHis");
        $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));
        $vnp_ExpireDate = $expire;
        //Billing
        $vnp_Bill_Mobile = $request->phone;
        $vnp_Bill_Email = $request->email;
        $fullName = trim($request->name);
        if (isset($fullName) && trim($fullName) != '') {
            $name = explode(' ', $fullName);
            $vnp_Bill_FirstName = array_shift($name);
            $vnp_Bill_LastName = array_pop($name);
        }

        $vnp_Bill_Address = $request->address;
        $vnp_Bill_City = $request->city;
        $vnp_Bill_Country = $request->txt_bill_country;

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $vnp_ExpireDate,
            "vnp_Bill_Mobile" => $vnp_Bill_Mobile,
            "vnp_Bill_Email" => $vnp_Bill_Email,
            "vnp_Bill_FirstName" => $vnp_Bill_FirstName,
            "vnp_Bill_LastName" => $vnp_Bill_LastName,
            "vnp_Bill_Address" => $vnp_Bill_Address,
            "vnp_Bill_City" => $vnp_Bill_City,
            "vnp_Bill_Country" => $vnp_Bill_Country,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        return response()->json(['tu' => $request->all(), 'url' => $vnp_Url]);
    }

    public function returnUrl(Request $request)
    {
        if ($request->vnp_ResponseCode == '00') {
            Cart::clear();
            return Redirect::to("home?messageSuccess=Thanh to??n th??nh c??ng");
        } else {
            return Redirect::to("home?messageError=Thanh to??n th???t b???i");
        }
    }
}
