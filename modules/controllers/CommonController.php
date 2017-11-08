<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/13
 * Time: 8:22
 */

namespace app\modules\controllers;

use yii\web\Controller;

use app\modules\controllers\PublicController;

use Yii;

class CommonController extends Controller
{
    public function init()
    {
        if (Yii::$app->session['admin']['isLogin'] != 1) {
            return $this->redirect(['/admin/public/login']);
        }
    }
}