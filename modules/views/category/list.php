<link rel="stylesheet" href="assets/admin/css/compiled/user-list.css" type="text/css" media="screen"/>
<!-- main container -->
<div class="content">

    <div class="container-fluid">
        <div id="pad-wrapper" class="users-list">
            <div class="row-fluid header">
                <h3>分类列表</h3>
                <div class="span10 pull-right">
                    <a href="<?php echo yii\helpers\Url::to(['category/add']) ?>" class="btn-flat success pull-right">
                        <span>&#43;</span>
                        添加新分类
                    </a>
                </div>
            </div>

            <!-- Users table -->
            <div class="row-fluid table">
                <table class="table table-hover">
                    <?php
                    echo Yii::$app->session->getFlash('info');
                    ?>
                    <thead>
                    <tr>
                        <th class="span3 sortable">
                            <span class="line"></span>分类名称
                        </th>
                        <th class="span3 sortable align-right">
                            <span class="line"></span>操作
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($lists as $list) : ?>
                        <tr class="first">
                            <td>
                                <?php echo $list['title'] ?>
                            </td>
                            <td class="align-right">
                                <a href="<?php echo yii\helpers\Url::to(['category/updatecate', 'cateid' => $list['cateid']]); ?>">编辑</a>
                                <a href="<?php echo yii\helpers\Url::to(['category/del', 'cateid' => $list['cateid']]) ?>">删除</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
            <div class="pagination pull-right">
                <?php /*echo yii\widgets\LinkPager::widget([
                        'pagination' => $pager,
                        'prevPageLabel' => '&#8249;',
                        'nextPageLabel' => '&#8250;',
                        ]);*/ ?>
            </div>
            <!-- end users table -->
        </div>
    </div>
</div>
<!-- end main container -->
