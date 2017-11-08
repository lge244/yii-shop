<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/9
 * Time: 19:53
 */

namespace app\controllers;

use app\models\Test;

use app\controllers\CommonController;

class IndexController extends CommonController
{
    public function actionIndex()
    {
        $this->layout = "layout1";
        return $this->render("index");
    }
}