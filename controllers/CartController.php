<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/10
 * Time: 8:26
 */

namespace app\controllers;

use app\models\Cart;
use app\models\Product;
use yii\web\Controller;

use Yii;
use app\models\User;
use app\controllers\CommonController;

class CartController extends CommonController
{
    public function actionIndex()
    {
        $this->layout = "layout2";
        if (Yii::$app->session['user']['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }
        $userid = User::find()->where('username = :name', [':name' => Yii::$app->session['user']['loginName']])->one()->userid;
        //asArray（）把查询到的表对象，转成数组
        $cart = Cart::find()->where('userid = :id', [':id' => $userid])->asArray()->all();
        $data = [];
        foreach ($cart as $k => $pro) {
            $product = Product::find()->where('productid = :id', [':id' => $pro['productid']])->one();
            $data[$k]['cover'] = $product->cover;
            $data[$k]['title'] = $product->title;
            $data[$k]['productnum'] = $pro['productnum'];
            $data[$k]['price'] = $pro['price'];
            $data[$k]['productid'] = $pro['productid'];
            $data[$k]['cartid'] = $pro['cartid'];
        }
        return $this->render("index", ['data' => $data]);
    }

    //加入购物车
    public function actionAdd()
    {
        //判断是否登陆，未登录跳转到登陆页面
        if (\Yii::$app->session['user']['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }
        $userid = User::find()->where('username = :name', [':name' => Yii::$app->session['user']['loginName']])->one()->userid;
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $num = 1;
//            $num = Yii::$app->request->post()['productnum'];
            $data['Cart'] = $post;
            $data['Cart']['productnum'] = $num;
            $data['Cart']['userid'] = $userid;
        }
        if (Yii::$app->request->isGet) {
            $productid = Yii::$app->request->get('productid');
            $model = Product::find()->where('productid = :id', [':id' => $productid])->one();
            $price = $model->issale ? $model->saleprice : $model->price;
            $num = 1;
            $data['Cart'] = ['productid' => $productid, 'productnum' => $num, 'price' => $price, 'userid' => $userid];

        }
        if (!$model = Cart::find()->where('productid = :productid and userid = :userid', [':productid' => $data['Cart']['productid'], ':userid' => $userid])->one()) {
            $model = new Cart();
        } else {
            $data['Cart']['productnum'] = $model->productnum + $num;
        }
        $data['Cart']['createtime'] = time();
        $model->load($data);
        $model->save();
        return $this->redirect(['cart/index']);
    }

    public function actionMod()
    {
        $cartid = Yii::$app->request->get('cartid');
        $productnum = Yii::$app->request->get('productnum');
        Cart::updateAll(['productnum' => $productnum], 'cartid=:id', [':id' => $cartid]);
    }

    public function actionDel()
    {
        $cartid = Yii::$app->request->get('cartid');
        Cart::deleteAll('cartid = :id', [':id' => $cartid]);
        return $this->redirect(['cart/index']);
    }
}