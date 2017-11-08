<!-- main container -->
<div class="content">

    <div class="container-fluid">
        <div id="pad-wrapper" class="users-list">
            <div class="row-fluid header">
                <h3>会员列表</h3>
                <div class="span10 pull-right">

                    <a href="<?php echo yii\helpers\Url::to(['user/reg']); ?>" class="btn-flat success pull-right">
                        <span>&#43;</span>
                        添加新用户
                    </a>
                </div>
            </div>

            <!-- Users table -->
            <div class="row-fluid table">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th class="span2">
                            用户名
                        </th>
                        <th class="span2">
                            <span class="line"></span>真实姓名
                        </th>
                        <th class="span2">
                            <span class="line"></span>年龄
                        </th>
                        <th class="span3">
                            <span class="line"></span>性别
                        </th>
                        <th class="span3">
                            <span class="line"></span>出生日期
                        </th>
                        <th class="span2">
                            <span class="line"></span>注册时间
                        </th>
                        <th class="span2">
                            <span class="line"></span>操作
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- row -->
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td>
                                <?php echo $user->username; ?>
                            </td>
                            <td>
                                <?php echo isset($user->profile->truename) ? $user->profile->truename : '未填写'; ?>
                            </td>
                            <td>
                                <?php echo isset($user->profile->age) ? $user->profile->age : '未填写'; ?>
                            </td>
                            <td>
                                <?php echo isset($user->profile->sex) ? $user->profile->sex : '未填写'; ?>
                            </td>
                            <td>
                                <?php echo isset($user->profile->birthday) ? $user->profile->birthday : '未填写'; ?>
                            </td>
                            <td>
                                <?php echo date('Y-m-d H:i:s', $user->createtime); ?>
                            </td>
                            <td class="align-right">
                                <a href="<?php echo yii\helpers\Url::to(['user/del', 'userid' => $user->userid]) ?>">删除</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php
                if (Yii::$app->session->hasFlash('info')) {
                    echo Yii::$app->session->getFlash('info');
                }
                ?>
            </div>
            <div class="pagination pull-right">

            </div>
            <!-- end users table -->
        </div>
    </div>
</div>
<!-- end main container -->

11