<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/11
 * Time: 16:29
 */

namespace app\modules\controllers;

use app\modules\models\Admin;

use yii\web\Controller;
use app\controllers\CommonController;
use yii\data\Pagination;

use Yii;

class ManageController extends CommonController
{
//   分页显示
    public function actionManagers()
    {
        $this->layout = "layout1";
//        连接数据库
        $model = Admin::find();
//        查询数据库里数据的总条数
        $count = $model->count();
//        接收分页总页数，来自config下的params.php
        $pageSize = Yii::$app->params['pageSize']['manage'];
//      实例化数据分页类
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
        //查询到每页对应的数据
        $managers = $model->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render("managers", ['managers' => $managers, 'pager' => $pager]);
    }

//  添加管理员
    public function actionReg()
    {
        $this->layout = "layout1";
        $model = new Admin();
//        判断是否是post过来的数据
        if (Yii::$app->request->isPost) {
//           将post提交过来的数据赋值给$post
            $post = Yii::$app->request->post();

//           判断model层添加的结果，并显示对应的信息
            if ($model->reg($post)) {
                Yii::$app->session->setFlash('info', '添加成功！');
            } else {
                Yii::$app->session->setFlash('info', '添加失败！');
            }
        }
//        添加后，密码和确认密码为空
        $model->adminpass = '';
        $model->repass = '';
        return $this->render('reg', ['model' => $model]);
    }

//  删除管理员
    public function actionDel()
    {
//        接收需要删除用户的id，并转为整型
        $adminid = (int)Yii::$app->request->get('adminid');
//        判断id是否为空，为空直接跳转到当前页面
        if (empty($adminid)) {
            $this->redirect(['manage/managers']);
        }
        $model = new Admin();
//        判断删除的结果，删除成功跳转到原页面，显示提示信息
        if ($model->deleteAll('adminid = :id', [':id' => $adminid])) {
            Yii::$app->session->setFlash('info', '删除成功！');
            $this->redirect(['manage/managers']);
        }
    }

//    修改管理员邮箱
    public function actionChangeemail()
    {
        $this->layout = 'layout1';
//        查询本用户的数据传递到模板里
        $model = Admin::find()->where('adminuser=:user', [':user' => Yii::$app->session['admin']['adminuser']])->one();
//       验证数据是否是post提交过来的
        if (Yii::$app->request->isPost) {
//            将post传递过来的数据赋值给 $post
            $post = Yii::$app->request->post();
//            验证修改的结果，并对应显示

            $sql = "SELECT adminid FROM shop_admin";


            if ($model->changeemail($post)) {
                Yii::$app->session->setFlash('info', '修改成功！');
            }
        }
        $model->adminpass = '';
        return $this->render('changeemail', ['model' => $model]);
    }

    //修改管理员密码
    public function actionChangepass()
    {
        $this->layout = 'layout1';
        $model = Admin::find()->where('adminuser = :user', [':user' => Yii::$app->session['admin']['adminuser']])->one();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->changepass($post)) {
                Yii::$app->session->setFlash('info', '修改成功！');
            }
        }
        $model->adminpass = '';
        $model->repass = '';
        return $this->render('changepass', ['model' => $model]);
    }
}