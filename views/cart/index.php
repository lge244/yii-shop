<?php

use yii\bootstrap\ActiveForm;

?>
    <!-- ============================================================= HEADER : END ============================================================= -->
    <section id="cart-page">
        <div class="container">
            <!-- ========================================= CONTENT ========================================= -->
            <?php $form = ActiveForm::begin([
                'action' => yii\helpers\Url::to(['order/add']),
            ]) ?>
            <div class="col-xs-12 col-md-9 items-holder no-margin">
                <?php $total = 0; ?>
                <?php foreach ($data

                               as $k => $pro): ?>
                    <input type="hidden" name="OrderDetail[<?php echo $k ?>][productid]"
                           value="<?php echo $pro['productid'] ?>">
                    <input type="hidden" name="OrderDetail[<?php echo $k ?>][price]"
                           value="<?php echo $pro['price'] ?>">
                    <input type="hidden" name="OrderDetail[<?php echo $k ?>][productnum]"
                           value="<?php echo $pro['productnum'] ?>">
                    <div class="row no-margin cart-item">
                        <div class="col-xs-12 col-sm-2 no-margin">
                            <a href="#" class="thumb-holder">
                                <img class="lazy" alt="" src="http://<?php echo $pro['cover'] ?>"/>
                            </a>
                        </div>

                        <div class="col-xs-12 col-sm-5">
                            <div class="title">
                                <a href="<?php echo yii\helpers\Url::to(['product/detail', 'productid' => $pro['productid']]) ?>"><?php echo $pro['title'] ?></a>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-3 no-margin">
                            <div class="quantity">
                                <div class="le-quantity">

                                    <a class="minus" href="#reduce"></a>
                                    <input name="productnum" id="<?php echo $pro['cartid'] ?>" readonly="readonly"
                                           type="text" value="<?php echo $pro['productnum'] ?>"/>
                                    <a class="plus" href="#add"></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-2 no-margin">
                            <div class="price">
                                ￥<?php echo $pro['price'] ?>
                            </div>
                            <a class="close-btn"
                               href="<?php echo yii\helpers\Url::to(['cart/del', 'cartid' => $pro['cartid']]) ?>"></a>
                        </div>
                    </div><!-- /.cart-item -->
                    <?php $total += $pro['price'] * $pro['productnum'] ?>
                <?php endforeach; ?>
            </div>
            <!-- ========================================= CONTENT : END ========================================= -->

            <!-- ========================================= SIDEBAR ========================================= -->

            <div class="col-xs-12 col-md-3 no-margin sidebar ">
                <div class="widget cart-summary">
                    <h1 class="border">购物车详细</h1>
                    <div class="body">
                        <ul class="tabled-data no-border inverse-bold">
                            <li>
                                <label>总共金额</label>
                                <div class="value pull-right">￥<?php echo $total ?></div>
                            </li>
                            <li>
                                <label>快递费</label>
                                <div class="value pull-right">包邮</div>
                            </li>
                        </ul>
                        <ul id="total-price" class="tabled-data inverse-bold no-border">
                            <li>
                                <label>付款金额</label>
                                <div class="value pull-right">￥<?php echo $total ?></div>
                            </li>
                        </ul>
                        <div class="buttons-holder">
                            <input type='submit' class="le-button big" value="去结算">
                            <a class="simple-link block"
                               href="http://localhost/~ibrahim/themeforest/HTML/mediacenter/upload/PHP/home">继续购物</a>
                        </div>
                    </div>
                </div><!-- /.widget -->

            </div><!-- /.sidebar -->
            <!-- ========================================= SIDEBAR : END ========================================= -->
        </div>
    </section>        <!-- ============================================================= FOOTER ============================================================= -->
<?php ActiveForm::end(); ?>