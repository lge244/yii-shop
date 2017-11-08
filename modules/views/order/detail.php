<!-- main container -->
<div class="content">

    <div class="container-fluid">
        <div id="pad-wrapper" class="users-list">
            <div class="row-fluid header">
                <h3>订单详情</h3>
            </div>
            <div class="row-fluid">
                <p>订单编号：<?php echo $data['orderid'] ?> </p>
                <p>下单用户：<?php echo $data['username'] ?> </p>
                <p>收货地址：<?php if (!$data['address']) {
                        echo "未设置";
                    }
                    echo $data['address']; ?> </p>
                <p>订单总价：<?php echo $data['amount'] ?></p>
                <p>快递方式：<?php echo $data['express'] ?> </p>
                <p>快递编号：<?php if (!$data['expressno']) {
                        echo "未填写";
                    }
                    echo $data['expressno']; ?></p>
                <p>订单状态：<?php if ($data['status'] <= 201) {
                        echo "未付款";
                    } elseif ($data['status'] == 202) {
                        echo "未发货";
                    } elseif ($data['status'] == 220) {
                        echo "已发货";
                    } elseif ($data['status'] == 260) {
                        echo "已完成订单";
                    }
                    ?></p>
                <p>商品列表：</p>
                <?php foreach ($product as $pro): ?>
                    <p>
                        <img src="http://<?php echo $pro['cover'] ?>-logo">
                        <?php echo $pro['productnum'] ?> * <a
                                href="<?php echo yii\helpers\Url::to(['/product/detail', 'productid' => $pro['productid']]) ?>"><?php echo $pro['title'] ?></a>
                    </p>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
