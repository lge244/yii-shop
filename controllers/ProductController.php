<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/9
 * Time: 21:39
 */

namespace app\controllers;

use app\models\Product;
use yii\data\Pagination;
use yii\web\Controller;
use app\controllers\CommonController;

class ProductController extends CommonController
{
    public $layout = "layout2";

    public function actionIndex()
    {
        //查询所有本类的商品
        $cid = \Yii::$app->request->get('cateid');
        $where = 'cateid = :id and ison = 1';
        $params = [':id' => $cid];
        $model = Product::find()->Where($where, $params);
        $all = $model->asArray()->all();

        //所有商品分页处理
        $count = $model->count();
        $pageSize = \Yii::$app->params['pageSize']['allProducts'];
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
        $all = $model->offset($pager->offset)->limit($pager->limit)->asArray()->all();

        //查询每块的数据
        $tui = $model->Where($where . ' and istui = \'1\'', $params)->orderby('createtime desc')->limit(5)->asArray()->all();
        $hot = $model->Where($where . ' and ishot = \'1\'', $params)->orderby('createtime desc')->limit(5)->asArray()->all();
        $sale = $model->Where($where . ' and issale =\'1\'', $params)->orderby('createtime desc')->limit(5)->asArray()->all();
        return $this->render("index", ['all' => $all, 'tui' => $tui, 'hot' => $hot, 'sale' => $sale, 'pager' => $pager]);
    }

    public function actionDetail()
    {
        $productid = \Yii::$app->request->get('productid');
        $product = Product::find()->where('productid = :id', [':id' => $productid])->asArray()->all();
        $data['all'] = Product::find()->where('ison = 1')->orderBy('createtime desc')->limit(7)->all();
        return $this->render("detail", ['product' => $product, 'data' => $data]);
    }
}