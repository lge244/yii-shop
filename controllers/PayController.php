<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/20
 * Time: 10:41
 */

namespace app\controllers;

use app\controllers\CommonController;

use app\models\Pay;
use Yii;

class PayController extends CommonController
{
    public $enableCsrfValidation = false;

    public function actionNotify()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if (Pay::notify($post)) {
                echo "success";
                exit;
            }
            echo "fail";
            exit;
        }
    }

    public function actionReturn()
    {
        $this->layout = 'layout1';
        $status = Yii::$app->request->get('trader_status');
        if ($status == 'TRADE_SUCCESS') {
            $s = 'ok';
        } else {
            $s = 'no';
        }
        return $this->render('status', ['status' => $s]);
    }

    public function actionStatus202()
    {
        $this->layout = 'layout1';
        return $this->render('status202');
    }

    public function actionStatus100()
    {
        $this->layout = 'layout1';
        return $this->render('status100');
    }
}