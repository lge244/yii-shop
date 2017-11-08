<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/19
 * Time: 16:14
 */

namespace app\controllers;

use app\controllers\CommonController;

use app\models\Address;
use app\models\User;
use Yii;

class AddressController extends CommonController
{
    public function actionAdd()
    {
        if (Yii::$app->session['user']['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }
        $loginName = Yii::$app->session['user']['loginName'];
        $userid = User::find()->where('username = :name or useremail = :email', [':name' => $loginName, ':email' => $loginName])->one()->userid;
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $post['userid'] = $userid;
            $post['address'] = $post['address1'] . $post['address2'];
            $data['Address'] = $post;
            $model = new Address;
            $model->load($data);
            $model->save();
        }
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function actionDel()
    {
        $addressid = Yii::$app->request->get('addressid');
        Address::deleteAll('addressid = :id', [':id' => $addressid]);
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}