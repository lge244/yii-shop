<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/10
 * Time: 9:51
 */

namespace app\controllers;

use app\models\User;
use yii\web\Controller;
use Yii;
use app\controllers\CommonController;

class MemberController extends CommonController
{
    public function actionAuth()
    {
        $this->layout = "layout2";
        $model = new User();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->login($post)) {
                return $this->goBack(Yii::$app->request->referrer);
            }
        }
        return $this->render("auth", ['model' => $model]);
    }

    public function actionLogout()
    {
        unset(Yii::$app->session['user']);
        if (!Yii::$app->session['user']) {
            return $this->goBack(Yii::$app->request->referrer);
        }
    }

    public function actionQqlogin()
    {
        require_once('../vendor/qqlogin/qqConnectAPI.php');
        $qc = new \QC();
        $qc->qq_login();
    }

    public function actionQqcallback()
    {
        require_once('../vendor/qqlogin/qqConnectAPI.php');
        $auth = new \OAuth();
        $accessToken = $auth->qq_callback();
        $openid = $auth->get_openid();
        $session = Yii::$app->session;
        $session['openid'] = $openid;
        $user = User::find()->where('openid=:openid', [':openid' => $openid])->one();
        if ($user) {
            $session['user'] = [
                'loginName' => $user->username,
                'isLogin' => 1,
            ];

            return $this->redirect(['index/index']);
        }
        return $this->redirect(['member/qqreg']);

    }

    public function actionQqreg()
    {
        $this->layout = 'layout2';
        $model = new User();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $session = Yii::$app->session;
            $post['User']['openid'] = $session['openid'];
            if ($model->reg($post, 'qqreg')) {
                return $this->redirect(['index/index']);
            }
        }
        return $this->render('qqreg', ['model' => $model]);
    }
}