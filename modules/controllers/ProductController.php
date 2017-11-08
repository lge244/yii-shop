<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/16
 * Time: 9:01
 */

namespace app\modules\controllers;

use app\models\Category;
use app\models\Product;
use yii\data\Pagination;
use yii\web\Controller;
use Yii;
use crazyfd\qiniu\Qiniu;
use app\controllers\CommonController;

class ProductController extends CommonController
{
    public function actionList()
    {
        $this->layout = 'layout1';
        $model = Product::find();
        $count = $model->count();
        $pageSize = Yii::$app->params['pageSize']['product'];
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
        $products = $model->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('list', ['pager' => $pager, 'products' => $products]);
    }

    public function actionAdd()
    {
        $this->layout = 'layout1';
        $model = new Product();
        $cates = new Category();
        $list = $cates->setOptions();
        unset($list[0]);
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $pics = $this->upload();
            if (!$pics) {
                $model->addError('cover', '您还没上传封面图片！');
            } else {
                $post['Product']['cover'] = $pics['cover'];
            }
            if ($model->add($post)) {
                Yii::$app->session->setFlash('info', '添加成功！');
            } else {
                Yii::$app->session->setFlash('info', '添加失败！');
            }

        }
        return $this->render('add', ['model' => $model, 'opts' => $list]);
    }

    private function upload()
    {
        //判断上传的数据是否有错误，有错误直接返回false；
        if ($_FILES['Product']['error']['cover'] > 0) {
            return false;
        }
        //实例化七牛这个类
        $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
        //生成一个key ，来最为存在七牛中的图片名
        $key = uniqid();
        //上传数据
        $qiniu->uploadFile($_FILES['Product']['tmp_name']['cover'], $key);
        //上传以后的图片的外链
        $cover = $qiniu->getLink($key);
//        $pics = [];
//        foreach ($_FILES['Product']['tmp_name']['pics'] as $k => $file) {
//            if ($_FILES['Product']['error']['pics'][$k] > 0) {
//                continue;
//            }
//            $key = uniqid();
//            $qiniu->uploadFile($file, $key);
//            $pics[$key] = $qiniu->getLink($key);
//        }
        return ['cover' => $cover];

    }

    public function actionMod()
    {
        $this->layout = 'layout1';
        //实例化分类
        $cate = new Category();
        //将分类格式化
        $list = $cate->setOptions();
        unset($list[0]);
        //获取需要修改的数据id
        $productid = Yii::$app->request->get('productid');
        //查询需要修改数据，通过上面获取的id
        $model = Product::find()->where('productid=:id', [':id' => $productid])->one();
        if (Yii::$app->request->isPost) {
            //过去修改过的数据
            $post = Yii::$app->request->post();
            //实例化七牛类
            $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
            //获取未修改的封面图片外链接
            $post['Product']['cover'] = $model->cover;
            if ($_FILES['Product']['error']['cover'] == 0) {
                //生成一个随机的字符串，作为存在七牛中的文件名
                $key = uniqid();
                //上传数据
                $qiniu->uploadFile($_FILES['Product']['tmp_name']['cover'], $key);
                $post['Product']['cover'] = $qiniu->getLink($key);
                $qiniu->delete(basename($model->cover));
            }
            if ($model->load($post) && $model->save()) {
                Yii::$app->session->setFlash('info', '修改成功');

            }

        }
        return $this->render('add', ['model' => $model, 'opts' => $list]);
    }

    public function actionDel()
    {
        $productid = Yii::$app->request->get('productid');
        $model = Product::find()->where('productid = :id', [':id' => $productid])->one();
        //basename 获取路径中的文件名
        $key = basename($model->cover);
        //实例化七牛类
        $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
        if ($key) {
            //在七牛里删除这个文件
            $qiniu->delete($key);
        }
        //删除对应的数据
        if (Product::deleteAll('productid = :pid', [':pid' => $productid])) {
            Yii::$app->session->setFlash('info', '删除成功！');
        }
        return $this->redirect(['product/list']);

    }

    public function actionOn()
    {
        $productid = Yii::$app->request->get('productid');
        if (Product::updateAll(['ison' => '0'], 'productid=:id', [':id' => $productid])) {
            Yii::$app->session->setFlash('info', '上架成功');
        }
        return $this->redirect(['product/list']);
    }

    public function actionOff()
    {
        $productid = Yii::$app->request->get('productid');
        if (Product::updateAll(['ison' => '1'], 'productid = :id', [':id' => $productid])) {
            Yii::$app->session->setFlash('info', '下架成功');
        }
        return $this->redirect(['product/list']);
    }
}