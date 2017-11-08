<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/19
 * Time: 9:39
 */

namespace app\models;

use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
    const CREATEORDER = 0;
    const CHECKORDER = 100;
    const PAYFAILED = 201;
    const PAYSUCCESS = 202;
    const SENDED = 220;
    const RECEIVED = 260;
    public $products;
    //状态备注
    public static $status = [
        self::CREATEORDER => '订单初始化',
        self::CHECKORDER => '待支付',
        self::PAYFAILED => '支付失败',
        self::PAYSUCCESS => '等待发货',
        self::SENDED => '已发货',
        self::RECEIVED => '订单完成',
    ];

    public function rules()
    {
        return [
            [['userid', 'status'], 'required', 'on' => ['add']],
            [['addressid', 'expressid', 'amount', 'status'], 'required', 'on' => ['update']],
            ['createtime', 'safe', 'on' => ['add']]
        ];
    }

    public static function tableName()
    {
        return "{{%order}}";
    }

    public static function getProducts($userid)
    {
        //查询当前用户的所有订单
        $orders = self::find()->where('status > 0 and userid = :id', [':id' => $userid])->all();
        //查询当前订单的详情
        foreach ($orders as $order) {
            //查询当前订单的详情
            $details = OrderDetail::find()->where('orderid = :id', [':id' => $order->orderid])->all();
            $products = [];
            foreach ($details as $detail) {
                //查询当前详情下的商品
                $product = Product::find()->where('productid = :id', [':id' => $detail->productid])->one();
                //将详情里的商品数量赋值到商品下
                $product->num = $detail->productnum;
                $product->price = $detail->price;
                //查询当前商品所属的分类
                $product->cate = Category::find()->where('cateid = :id', [':id' => $product->cateid])->one()->title;
                $products[] = $product;

            }
            $order->products = $products;
        }
        return $orders;
    }
}