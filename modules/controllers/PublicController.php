<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/10
 * Time: 16:00
 */

namespace app\modules\controllers;

use yii\web\Controller;
use app\modules\models\Admin;
use Yii;
use app\controllers\CommonController;
class PublicController extends CommonController
{
    //不用官方的布局
    public $layout = false;

    //渲染登陆页面
    public function actionLogin()
    {
        //实例化admin
        $model = new Admin;
        //验证是否post传递的数据
        if (Yii::$app->request->isPost) {
            //接收post的数据
            $post = Yii::$app->request->post();
            //验证model层 login方法的返回值
            if ($model->login($post)) {
                $this->redirect(['default/index']);
                Yii::$app->end();
            }
        }

        return $this->render("login", ['model' => $model]);
    }

    //退出操作，删除session 并跳转到登陆页面
    public function actionLogout()
    {
        //删除session
        unset(Yii::$app->session['admin']);
        if (!isset(Yii::$app->session['admin'])) {
            //跳转页面
            $this->redirect(['public/login']);
            Yii::$app->end();
        }
        $this->goBack();
    }

    //渲染找回密码页面，
    public function actionSeekpassword()
    {
        //实例化admin
        $model = new Admin;
        //验证是否是post传递的数据
        if (Yii::$app->request->isPost) {
            //接收post的数据
            $post = Yii::$app->request->post();
            //把post传到model层 login方法里去
            $model->seekpass($post);
        }
        return $this->render("seekpassword", ['model' => $model]);
    }


}