<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/14
 * Time: 20:58
 */

namespace app\modules\controllers;

use yii\db\Exception;
use yii\web\Controller;
use app\controllers\CommonController;
use Yii;

use app\models\Category;

class CategoryController extends CommonController
{
    public function actionList()
    {
        $this->layout = 'layout1';
        $model = new Category();
        $lists = $model->setList();
        return $this->render('list', ['lists' => $lists]);

    }

    public function actionAdd()
    {
        $this->layout = 'layout1';
        $model = new Category();
        $list = $model->setOptions();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->add($post)) {
                Yii::$app->session->setFlash('info', '添加成功');
            }
        }
        return $this->render('add', ['model' => $model, 'list' => $list]);
    }

    public function actionUpdatecate()
    {
        $this->layout = 'layout1';
        $model = new Category();
        $list = $model->setOptions();
        $cateid = Yii::$app->request->get('cateid');
        $model = Category::find()->where('cateid=:id', [':id' => $cateid])->one();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->load($post) && $model->updateAll(['title' => $post['Category']['title']], 'cateid = :id', [':id' => $cateid])) {
                Yii::$app->session->setFlash('info', '修改成功！');
            } else {
                Yii::$app->session->setFlash('info', '修改失败！');
            }

        }
        return $this->render('updatecate', ['model' => $model, 'list' => $list]);
    }

    public function actionDel()
    {
        try {
            $cateid = Yii::$app->request->get('cateid');
            if (empty($cateid)) {
                throw new \Exception('参数错误');
            }
            $data = Category::find()->where('pid = :pid', [":pid" => $cateid])->one();
            if ($data) {
                throw new \Exception('该分类下有子类，不允许删除');
            }
            if (!Category::deleteAll('cateid = :id', [":id" => $cateid])) {
                throw new \Exception('删除失败');
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('info', $e->getMessage());
        }
        return $this->redirect(['category/list']);
    }
}