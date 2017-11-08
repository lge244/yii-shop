<link rel="stylesheet" href="assets/admin/css/compiled/user-list.css" type="text/css" media="screen"/>
<!-- main container -->
<div class="content">

    <div class="container-fluid">
        <div id="pad-wrapper" class="users-list">
            <div class="row-fluid header">
                <h3>订单列表</h3>
            </div>

            <!-- Users table -->
            <div class="row-fluid table">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th class="span2 sortable">
                            <span class="line"></span>订单编号
                        </th>
                        <th class="span2 sortable">
                            <span class="line"></span>下单人
                        </th>
                        <th class="span3 sortable">
                            <span class="line"></span>收货地址
                        </th>
                        <th class="span3 sortable">
                            <span class="line"></span>快递方式
                        </th>
                        <th class="span2 sortable">
                            <span class="line"></span>订单总价
                        </th>
                        <th class="span3 sortable">
                            <span class="line"></span>商品列表
                        </th>
                        <th class="span3 sortable">
                            <span class="line"></span>订单状态
                        </th>
                        <th class="span2 sortable align-right">
                            <span class="line"></span>操作
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- row -->
                    <?php foreach ($data as $pro): ?>
                        <tr class="first">
                            <td>
                                <?php echo $pro['orderid']; ?>
                            </td>
                            <td>
                                <?php echo $pro['username']; ?>
                            </td>
                            <td>
                                <?php echo $pro['address']; ?>
                            </td>
                            <td>
                                <?php echo $pro['express'] ?>
                            </td>
                            <td>
                                <?php echo $pro['amount']; ?>
                            </td>
                            <td>
                                <?php echo $pro['productnum'] . ' X ' . $pro['producttitle']; ?>...
                            </td>
                            <?php if ($pro['status'] == 0 || $pro['status'] == 100 || $pro['status'] == 201) {
                                $info = "error";
                                $message = '未付款';
                            }
                            if ($pro['status'] == 202) {
                                $info = "info";
                                $message = "未发货";
                            }
                            if ($pro['status'] == 220) {
                                $info = 'warning';
                                $message = "已经发货";
                            }
                            if ($pro['status'] == 260) {
                                $info = "success";
                                $message = "该订单已完成";
                            }
                            ?>

                            <td><span class="label label-<?php echo $info; ?>"> <?php echo $message; ?></span></td>
                            <td class="align-right">
                                <?php if ($pro['status'] == 202): ?>
                                    <a href="<?php echo yii\helpers\Url::to(['order/send', 'orderid' => $pro['orderid']]) ?>">发货</a>
                                <?php endif; ?>
                                <a href="<?php echo yii\helpers\Url::to(['order/detail', 'orderid' => $pro['orderid']]) ?>">查看</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="pagination pull-right">
                <?php echo yii\widgets\LinkPager::widget([
                    'pagination' => $pager,
                    'prevPageLabel' => '&#8249;',
                    'nextPageLabel' => '&#8250;',

                ]) ?>
            </div>
            <!-- end users table -->
        </div>
    </div>
</div>
<!-- end main container -->
