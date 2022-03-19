<?php

namespace App\Http\Service;

use App\Models\Order_detail;
use App\Models\Orders;
use Darryldecode\Cart\Facades\CartFacade;

class OrderService
{
    public function createOrder($requestAll,$request)
    {
        $order = new Orders();
        $order->fill($requestAll);
        $order->shipping_address = $request->address . ", " . $request->ward . ", " . $request->district . ", " . $request->city;
        $order->payment_type = "Thanh toán online";
        $order->payment_status = 1;
        $order->delivery_status = 1;
        $order->grand_total = $request->grand_total;
        $order->code = date('dmY-His');
        $order->key_orders = $request->key_orders;
        $order->save();
        $id_order = $order->id;
        $content = CartFacade::getContent();
        // dd($content, $request);

        foreach ($content as $key => $value) {
            $orderDetail = new Order_detail();

            $orderDetail->order_id = $id_order;
            $orderDetail->product_id = $value->id;
            $orderDetail->price = $value->price;

            $orderDetail->shipping_price = 0;

            $orderDetail->payment_status = "Chưa thanh toán";
            $orderDetail->delivery_status = "Đang chờ xử lý";

            $orderDetail->quantity = $value->quantity;
            $orderDetail->save();
        }
    }
}
