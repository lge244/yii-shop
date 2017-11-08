<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/10
 * Time: 8:38
 */

namespace app\controllers;

use app\models\Address;
use app\models\Cart;
use app\models\Product;
use app\models\Record;
use app\models\User;
use yii\db\Exception;
use yii\web\Controller;
use Yii;
use app\models\Order;
use app\models\OrderDetail;
use app\models\Pay;

class OrderController extends CommonController
{
    public $layout = "layout2";

    public function actionIndex()
    {
        if (Yii::$app->session['user']['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }
        $loginName = Yii::$app->session['user']['loginName'];
        $userid = User::find()->where('username = :name or useremail=:email', [':name' => $loginName, ':email' => $loginName])->one()->userid;
        $orders = Order::getProducts($userid);
        $status = [];
        foreach ($orders as $k => $order) {
            $orderid = $order->orderid;
            $status[$k]['status'] = Record::find()->where('orderid = :id', [':id' => $orderid])->one()->status;
        }
        return $this->render("index", ['orders' => $orders, 'status' => $status]);
    }

    public function actionCheck()
    {
        //判断是否登陆
        if (Yii::$app->session['user']['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }
        //获取orderid
        $orderid = Yii::$app->request->get('orderid');
        //查询status 订单状态
        $status = Order::find()->where('orderid = :id', [':id' => $orderid])->one()->status;
        //判断订单状态
        if ($status != Order::CREATEORDER && $status != Order::CHECKORDER) {
            return $this->redirect(['order/index']);
        }
        //获取到当前用户的用户，名
        $loginName = Yii::$app->session['user']['loginName'];
        //查询当前用户的id
        $userid = User::find()->where('username = :name or useremail = :email', [':name' => $loginName, ':email' => $loginName])->one()->userid;
        //查询当前用户的地址信息
        $addresses = Address::find()->where('userid = :id', [':id' => $userid])->asArray()->all();
        //获取订单详细
        $details = OrderDetail::find()->where('orderid = :id', [':id' => $orderid])->asArray()->all();
        $data = [];
        //遍历所有订单详细，并赋给$data[];
        foreach ($details as $detail) {
            $model = Product::find()->where('productid = :id', [':id' => $detail['productid']])->one();
            $detail['title'] = $model->title;
            $detail['cover'] = $model->cover;
            $data[] = $detail;
        }
        //获取快递名称
        $express = Yii::$app->params['express'];
        //获取快递价格
        $expressPrice = Yii::$app->params['expressPrice'];
        return $this->render("check", ['express' => $express, 'expressPrice' => $expressPrice, 'addresses' => $addresses, 'products' => $data]);
    }


    //去结算
    public function actionAdd()
    {
        if (Yii::$app->session['user']['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (Yii::$app->request->isPost) {
                //接收post提交过来的数据
                $post = Yii::$app->request->post();
                //实例话order
                $ordermodel = new Order();
                //设置添加环境
                $ordermodel->scenario = 'add';
                //查找用户信息
                $usermodel = User::find()->where('username = :name or useremail = :email', [':name' => Yii::$app->session['user']['loginName'], ':email' => Yii::$app->session['user']['loginName']])->one();
                if (!$usermodel) {
                    throw new \Exception();
                }
                //提取用户id
                $userid = $usermodel->userid;
                //将用户id写入到ordermodel里
                $ordermodel->userid = $userid;
                //将当前状态写入
                $ordermodel->status = Order::CREATEORDER;
                //写入当前时间
                $ordermodel->createtime = time();
                //判断是否成功的将数据添加到order表里
                if (!$ordermodel->save()) {
                    throw new \Exception();
                }
                //添加成功，获取订单id
                $orderid = $ordermodel->getPrimaryKey();
                foreach ($post['OrderDetail'] as $product) {
                    $model = new OrderDetail;
                    $product['orderid'] = $orderid;
                    $product['createtime'] = time();
                    $data['OrderDetail'] = $product;
                    //判断是否成功的把 data 写入到OrderDetail表中
                    if (!$model->add($data)) {
                        throw new \Exception();
                    }
                }
            }
            //执行事务
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->redirect(['cart/index']);
        }
        return $this->redirect(['order/check', 'orderid' => $orderid]);
    }

    public function actionConfirm()
    {
        try {
            //判断是否登陆
            if (Yii::$app->session['user']['isLogin'] != 1) {
                return $this->redirect(['member/auth']);
            }
            //判断是否是post提交的数据
            if (!Yii::$app->request->isPost) {
                throw new \Exception();
            }
            //获取post的数据
            $post = Yii::$app->request->post();
            //获取当前用户的用户名
            $loginName = Yii::$app->session['user']['loginName'];
            //根据当前用户名查询当前用户的信息
            $userModel = User::find()->where('username = :name or useremail = :email', [':name' => $loginName, 'email' => $loginName])->one();
            //判断用户是否存在
            if (empty($userModel)) {
                throw new \Exception();
            }
            //获取用户id
            $userid = $userModel->userid;
            //根据post过来的订单id和用户id查询对应订单
            $orderModel = Order::find()->where('orderid = :oid and userid = :uid', [':oid' => $post['orderid'], ':uid' => $userid])->one();
            //判断对应订单是否存在
            if (empty($orderModel)) {
                throw new \Exception();
            }
            //设置环境为更新操作
            $orderModel->scenario = 'update';
            //设置状态为未支付状态
            $post['status'] = Order::CHECKORDER;
            //根据订单id查询对应的订单详细
            $details = OrderDetail::find()->where('orderid = :id', [':id' => $post['orderid']])->all();
            $amount = 0;
            //遍历当前订单的所有详细信息
            foreach ($details as $detail) {
                $amount += $detail->productnum * $detail->price;
            }
            if ($amount <= 0) {
                throw new \Exception();
            }
            $express = Yii::$app->params['expressPrice'][$post['expressid']];
            if ($express < 0) {
                throw new \Exception();
            }
            $amount += $express;
            $post['amount'] = $amount;
            $data['Order'] = $post;
            if ($orderModel->load($data) && $orderModel->save()) {
                return $this->redirect(['order/pay', 'orderid' => $post['orderid'], 'paymethod' => $post['paymethod']]);
            }
        } catch (\Exception $e) {
            return $this->redirect(['index/index']);
        }
    }

    //付款页面
    public function actionPay()
    {

        $orderid = Yii::$app->request->get('orderid');
        $paymethod = Yii::$app->request->get('paymethod');
        if (empty($orderid) || empty($paymethod)) {
            exit;
        }
        $username = Yii::$app->session['user']['loginName'];
        $userid = User::find()->where('username = :name or useremail = :email', [':name' => $username, ':email' => $username])->one()->userid;
        $order = Order::find()->where('orderid = :id', [':id' => $orderid])->one();
        $express = Yii::$app->params['express']["$order->expressid"];
        $status = Order::PAYSUCCESS;
        $address = Address::find()->where('addressid = :id', [':id' => $order->addressid])->one()->address;
        $data = [];
        $data['orderid'] = $orderid;
        $data['address'] = $address;
        $data['paymethod'] = $paymethod;
        $data['express'] = $express;
        $data['status'] = $status;
        $data['createtime'] = time();
        $model = new Record;
        $record = Record::find()->where('orderid=:id', [':id' => $orderid])->one();
        if (empty($record)) {
            $model->scenario = 'add';
            $model->attributes = $data;
            if ($model->save()) {
                Cart::deleteAll('userid = :id', [':id' => $userid]);
                return $this->redirect(['pay/status100']);
            }
        }
        $status = $record->status;
        if ($status == Order::PAYSUCCESS) {
            return $this->redirect(['pay/status202']);
        }

        return $this->redirect(['order/index']);
    }

    public function actionReceived()
    {
        $orderid = Yii::$app->request->get('orderid');
        $record = Record::find()->where('orderid=:id', [':id' => $orderid])->one();
        if (!empty($record) && $record->status == Order::SENDED) {
            $record->status = Order::RECEIVED;
            $record->save();
        }
        return $this->redirect(['order/index']);
    }

//    public function actionPay()
//    {
//        try {
//            $orderid = Yii::$app->request->get('orderid');
//            $paymethod = Yii::$app->request->get('paymethod');
//            if (empty($orderid) || empty($paymethod)) {
//                throw new \Exception();
//            }
//            if ($paymethod == 'alipay') {
//                return Pay::alipay($orderid);
//            }
//        } catch (\Exception $e) {
//            return $this->redirect(['order/index']);
//        }
//    }
}