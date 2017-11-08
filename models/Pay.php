<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/20
 * Time: 8:13
 */

namespace app\models;


use app\models\OrderDetail;
use app\models\Product;
use AlipayPay;
use app\models\Order;

class Pay
{
    public static function alipay($orderid)
    {
        $amount = Order::find()->where('orderid = :id', [':id' => $orderid])->one()->amount;
        if (!empty($amount)) {
            $alipay = new AlipayPay;
            $giftname = '慕课商城';
            $data = OrderDetail::find()->where('orderid = :id', [':id' => $orderid])->all();
            $body = '';
            foreach ($data as $pro) {
                $body = Product::find()->where('productid =:id', [':id' => $pro['productid']])->one()->title . "-";
            }
            $body .= "等商品";
            $showUrl = "http://shop.mr-jason.com";
            $html = $alipay->requestPay($orderid, $amount, $giftname, $body, $showUrl);
            echo $html;
        }

    }

    public static function notify($data)
    {
        $alipay = new AlipayPay();
        //获取验证的结果
        $verify_result = $alipay->verifyNotify();
        //判断结果是否存在
        if ($verify_result) {
            //获取订单id号
            $out_trade_no = $data['extra_common_param'];
            $trade_no = $data['trade_no'];
            //获取支付状态
            $trader_status = $data['trade_status'];
            //设置状态为支付失败
            $status = Order::PAYFAILED;
            if ($trader_status == "TRADE_FINISHED" || $trader_status == "TRADE_SUCCESS") {
                $status = Order::PAYSUCCESS;
                $order_info = Order::find()->where('orderid = :id', [':id' => $out_trade_no])->one();
                if (!$order_info) {
                    return false;
                }
                if ($order_info->status == Order::CHECKORDER) {
                    Order::updateAll(['status' => $status], 'orderid =:id', [':id' => $order_info->orderid]);
                } else {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }

    }
}