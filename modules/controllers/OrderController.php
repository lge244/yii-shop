<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/21
 * Time: 8:19
 */

namespace app\modules\controllers;

use app\models\Address;
use app\models\Order;
use app\models\OrderDetail;
use app\models\Product;
use app\models\Record;
use app\models\User;
use yii\data\Pagination;
use yii\web\Controller;
use app\controllers\CommonController;
use Yii;

class OrderController extends CommonController
{
    //订单列表
    public function actionList()
    {
        $this->layout = 'layout1';
        $model = Record::find();
        $count = $model->count();
        $pageSize = Yii::$app->params['pageSize']['Record'];
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
        $list = $model->offset($pager->offset)->limit($pager->limit)->all();
        $data = [];
        foreach ($list as $k => $pro) {
            $orderid = $pro->orderid;
            $order[$k] = Order::find()->where('orderid = :id', [':id' => $orderid])->asArray()->one();
            $username[$k] = User::find()->where('userid = :id', [':id' => $order[$k]['userid']])->one()->username;
            $address[$k] = Address::find()->where('addressid = :id', [':id' => $order[$k]['addressid']])->one()->address;
            $productnum[$k] = OrderDetail::find()->where('orderid =:id', [':id' => $order[$k]['orderid']])->one()->productnum;
            $productid[$k] = OrderDetail::find()->where('orderid = :id', [':id' => $order[$k]['orderid']])->one()->productid;
            $producttitle[$k] = Product::find()->where('productid = :id', [':id' => $productid[$k]])->one()->title;
            $amount[$k] = $order[$k]['amount'];
            $data[$k]['producttitle'] = $producttitle[$k];
            $data[$k]['productnum'] = $productnum[$k];
            $data[$k]['amount'] = $amount[$k];
            $data[$k]['status'] = $pro->status;
            $data[$k]['express'] = $pro->express;
            $data[$k]['orderid'] = $pro->orderid;
            $data[$k]['username'] = $username[$k];
            $data[$k]['address'] = $address[$k];

        }
        return $this->render('list', ['pager' => $pager, 'data' => $data]);
    }

    //订单查看
    public function actionDetail()
    {
        $this->layout = 'layout1';
        $orderid = Yii::$app->request->get('orderid');
        $data['orderid'] = $orderid;
        $userid = Order::find()->where('orderid=:id', [':id' => $orderid])->one()->userid;
        $data['username'] = User::find()->where('userid = :id', [':id' => $userid])->one()->username;
        $record = Record::find()->where('orderid = :id', [':id' => $orderid])->one();
        $data['amount'] = Order::find()->where('orderid=:id', [':id' => $orderid])->one()->amount;
        $data['express'] = $record->express;
        $data['status'] = $record->status;
        $data['address'] = Record::find()->where('orderid = :id', [':id' => $orderid])->one()->address;
        $data['expressno'] = Record::find()->where('orderid=:id', [':id' => $orderid])->one()->expressno;
        $orderDetail = OrderDetail::find()->where('orderid=:id', [':id' => $orderid])->all();
        $product = [];
        foreach ($orderDetail as $k => $pro) {
            $productid = $pro->productid;
            $product[$k]['productnum'] = $pro->productnum;
            $product[$k]['title'] = Product::find()->where('productid=:id', [':id' => $productid])->one()->title;
            $product[$k]['cover'] = Product::find()->where('productid = :id', [':id' => $productid])->one()->cover;
            $product[$k]['productid'] = $productid;
        }
        return $this->render('detail', ['data' => $data, 'product' => $product]);
    }

    public function actionSend()
    {
        $this->layout = 'layout1';
        $model = new Record();
        $model->scenario = 'send';
        $orderid = Yii::$app->request->get('orderid');
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $status = Order::SENDED;
            if ($model->updateAll(array('status' => $status, 'expressno' => $post['Record']['expressno']), 'orderid = :id', [':id' => $orderid])) {
                Yii::$app->session->setFlash('info', '发货成功');
            }
        }
        return $this->render('send', ['model' => $model]);
    }
}