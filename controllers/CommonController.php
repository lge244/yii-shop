<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/17
 * Time: 13:54
 */

namespace app\controllers;

use app\models\Cart;
use app\models\Category;
use app\models\Product;
use app\models\Profile;
use yii\web\Controller;
use app\models\User;
use app\models\Categiry;
use Yii;

class CommonController extends Controller
{
    public function init()
    {

        $menu = Category::getMenu();
        $this->view->params['menu'] = $menu;
        $data = [];
        $data['products'] = [];
        $total = 0;
        if (Yii::$app->session['user']['isLogin']) {
            $usermodel = User::find()->where('username = :name', [':name' => Yii::$app->session['user']['loginName']])->one();
            if (!empty($usermodel) && !empty($usermodel->userid)) {
                $userid = $usermodel->userid;
                $carts = Cart::find()->where('userid=:id', [':id' => $userid])->asArray()->all();
                foreach ($carts as $k => $pro) {
                    $product = Product::find()->where('productid = :id', [':id' => $pro['productid']])->one();

                    $data['products'][$k]['cover'] = $product->cover;
                    $data['products'][$k]['title'] = $product->title;
                    $data['products'][$k]['productnum'] = $pro['productnum'];
                    $data['products'][$k]['productid'] = $pro['productid'];
                    $data['products'][$k]['cartid'] = $pro['cartid'];
                    $data['products'][$k]['price'] = $pro['price'];
                    $total += $data['products'][$k]['productnum'] * $data['products'][$k]['price'];
                }
            }
        }
        $data['total'] = $total;
        $this->view->params['cart'] = $data;

    }
}