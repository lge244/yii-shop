<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/12
 * Time: 22:10
 */

namespace app\modules\controllers;

use yii\web\Controller;

use app\models\user;

use yii\data\Pagination;

use app\models\Profile;
use app\controllers\CommonController;
use Yii;

class UserController extends CommonController
{
    public function actionUsers()
    {
        $this->layout = 'layout1';
        $model = User::find()->joinWith('profile');
        $count = $model->count();
        $pageSize = Yii::$app->params['pageSize']['user'];
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
        $users = $model->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('users', ['users' => $users, 'pager' => $pager]);
    }

    public function actionReg()
    {
        $this->layout = 'layout1';
        $model = new User();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->reg($post)) {
                Yii::$app->session->setFlash('info', '添加成功！');
            } else {
                Yii::$app->session->setFlash('info', '添加失败！');
            }
        }
        $model->userpass = '';
        $model->repass = '';
        return $this->render('reg', ['model' => $model]);
    }

    public function actionDel()
    {
        try {
            $userid = (int)Yii::$app->request->get('userid');
            if (empty($userid)) {
                throw new \Exception();
            }
            //创建事务
            $trans = Yii::$app->db->beginTransaction();
            //查询会员的详细信息
            if ($obj = Profile::find()->where('userid = :id', [':id' => $userid])->one()) {
                //删除会员详细信息
                $res = Profile::deleteAll('userid=:id', [':id' => $userid]);
                if (empty($res)) {
                    throw new \Exception();
                }
            }
            //删除会员基本信息
            if (!User::deleteAll('userid = :id', [':id' => $userid])) {
                throw new \Exception();
            }
            //提交事务
            $trans->commit();
        } catch (\Exception $e) {
            //事务回滚
            if (Yii::$app->db->getTransaction()) {
                $trans->rollback();
            }
        }
        $this->redirect(['user/users']);
    }
}