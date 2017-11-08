<!-- ============================================================= HEADER : END ============================================================= -->
<section id="category-grid">
    <div class="container">

        <!-- ========================================= SIDEBAR ========================================= -->
        <div class="col-xs-12 col-sm-3 no-margin sidebar narrow">

            <!-- ========================================= PRODUCT FILTER ========================================= -->
            <div class="widget">
                <h1>商品筛选</h1>
                <div class="body bordered">

                    <div class="category-filter">
                        <h2>品牌</h2>
                        <hr>
                        <ul>
                            <li><input checked="checked" class="le-checkbox" type="checkbox"/> <label>三星</label> <span
                                        class="pull-right">(2)</span></li>
                            <li><input class="le-checkbox" type="checkbox"/> <label>戴尔</label> <span class="pull-right">(8)</span>
                            </li>
                            <li><input class="le-checkbox" type="checkbox"/> <label>东芝</label> <span class="pull-right">(1)</span>
                            </li>
                            <li><input class="le-checkbox" type="checkbox"/> <label>苹果</label> <span class="pull-right">(5)</span>
                            </li>
                        </ul>
                    </div><!-- /.category-filter -->

                    <div class="price-filter">
                        <h2>价格</h2>
                        <hr>
                        <div class="price-range-holder">

                            <input type="text" class="price-slider" value="">

                            <span class="min-max">
                    价格: $89 - $2899
                </span>
                            <span class="filter-button">
                    <a href="#">Filter</a>
                </span>
                        </div>
                    </div><!-- /.price-filter -->

                </div><!-- /.body -->
            </div><!-- /.widget -->
            <!-- ========================================= PRODUCT FILTER : END ========================================= -->
            <div class="widget">
                <h1 class="border">活动商品</h1>
                <ul class="product-list">
                    <?php foreach ($sale as $pro): ?>
                        <li>
                            <div class="row">
                                <div class="col-xs-4 col-sm-4 no-margin">
                                    <a href="<?php echo yii\helpers\Url::to(['product/detail', 'productid' => $pro['productid']]) ?>"
                                       class="thumb-holder">
                                        <img alt="" src="http://<?php echo $pro['cover'] ?>-logo"/>
                                    </a>
                                </div>
                                <div class="col-xs-8 col-sm-8 no-margin">
                                    <a href="<?php echo yii\helpers\Url::to(['product/detail', 'productid' => $pro['productid']]) ?>"><?php echo $pro['title'] ?></a>
                                    <div class="price">
                                        <div class="price-prev"><?php echo $pro['price'] ?></div>
                                        <div class="price-current"><?php echo $pro['saleprice'] ?></div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div><!-- /.widget -->
            <!-- ========================================= FEATURED PRODUCTS ========================================= -->
            <div class="widget">
                <h1 class="border">推荐商品</h1>
                <ul class="product-list">

                    <?php
                    if (!$tui) {
                        echo '暂无推荐商品！';
                    }
                    foreach ($tui as $pro): ?>
                        <li class="sidebar-product-list-item">
                            <div class="row">
                                <div class="col-xs-4 col-sm-4 no-margin">
                                    <a href="<?php echo yii\helpers\Url::to(['product/detail', 'productid' => $pro['productid']]) ?>"
                                       class="thumb-holder">
                                        <img alt="" src="http://<?php echo $pro['cover'] ?>-logo"/>
                                    </a>
                                </div>
                                <div class="col-xs-8 col-sm-8 no-margin">
                                    <a href="<?php echo yii\helpers\Url::to(['product/detail', 'productid' => $pro['productid']]) ?>"><?php echo $pro['title'] ?></a>
                                    <div class="price">
                                        <div class="price-prev"><?php echo $pro['price'] ?></div>
                                        <div class="price-current"><?php echo $pro['saleprice'] ?></div>
                                    </div>
                                </div>
                            </div>
                        </li><!-- /.sidebar-product-list-item -->
                    <?php endforeach; ?>
                </ul><!-- /.product-list -->
            </div><!-- /.widget -->
            <!-- ========================================= FEATURED PRODUCTS : END ========================================= -->
        </div>
        <!-- ========================================= SIDEBAR : END ========================================= -->

        <!-- ========================================= CONTENT ========================================= -->

        <div class="col-xs-12 col-sm-9 no-margin wide sidebar">

            <section id="recommended-products" class="carousel-holder hover small">

                <div class="title-nav">
                    <h2 class="inverse">热卖商品</h2>
                    <div class="nav-holder">
                        <a href="#prev" data-target="#owl-recommended-products"
                           class="slider-prev btn-prev fa fa-angle-left"></a>
                        <a href="#next" data-target="#owl-recommended-products"
                           class="slider-next btn-next fa fa-angle-right"></a>
                    </div>
                </div><!-- /.title-nav -->
                <?php foreach ($hot as $pro): ?>
                    <div id="owl-recommended-products" class="owl-carousel product-grid-holder">

                        <div class="no-margin carousel-item product-item-holder hover size-medium">
                            <div class="product-item">
                                <div class="image">
                                    <img alt="" src="http://<?php echo $pro['cover'] ?>"/>
                                </div>
                                <div class="body">

                                    <div class="title">
                                        <a href="<?php echo yii\helpers\Url::to(['product/detail', 'productid' => $pro['productid']]) ?>"><?php echo $pro['title'] ?></a>
                                    </div>
                                </div>
                                <div class="prices">

                                    <div class="price-current text-right"><?php echo $pro['saleprice'] ?></div>
                                </div>
                                <div class="hover-area">
                                    <div class="add-cart-button">
                                        <a href="<?php echo yii\helpers\Url::to(['cart/add', 'productid' => $pro['productid']]) ?>"
                                           class="le-button">加入购物车</a>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.carousel-item -->

                    </div><!-- /#recommended-products-carousel .owl-carousel -->

                <?php endforeach; ?>
            </section><!-- /.carousel-holder -->
            <section id="gaming">
                <div class="grid-list-products">
                    <h2 class="section-title">所有商品</h2>

                </div><!-- /.control-bar -->

                <div class="tab-content">
                    <div id="grid-view" class="products-grid fade tab-pane in active">

                        <div class="product-grid-holder">
                            <div class="row no-margin">
                                <?php foreach ($all as $pro): ?>
                                    <div class="col-xs-12 col-sm-4 no-margin product-item-holder hover">
                                        <div class="product-item">
                                            <?php if ($pro['ishot']): ?>
                                                <div class="ribbon red"><span>热！！</span></div>
                                            <?php endif; ?>
                                            <?php if ($pro['issale']): ?>
                                                <div class="ribbon green"><span>促销！！</span></div>
                                            <?php endif; ?>
                                            <?php if ($pro['istui']): ?>
                                                <div class="ribbon blue"><span>推荐！！</span></div>
                                            <?php endif; ?>

                                            <div class="image">
                                                <img alt="" src=" http://<?php echo $pro['cover'] ?>"/>
                                            </div>
                                            <div class="body">
                                                <div class="title">
                                                    <a href="<?php echo yii\helpers\Url::to(['product/detail', 'productid' => $pro['productid']]) ?>"><?php echo $pro['title'] ?></a>
                                                </div>
                                            </div>
                                            <div class="prices">
                                                <div class="price-prev">￥<?php echo $pro['price'] ?></div>
                                                <div class="price-current pull-right">
                                                    ￥<?php echo $pro['saleprice'] ?></div>
                                            </div>
                                            <div class="hover-area">
                                                <div class="add-cart-button">
                                                    <a href="<?php echo yii\helpers\Url::to(['cart/add', 'productid' => $pro['productid']]) ?>"
                                                       class="le-button">加入购物车</a>
                                                </div>
                                            </div>
                                        </div><!-- /.product-item -->
                                    </div><!-- /.product-item-holder -->
                                <?php endforeach; ?>
                            </div><!-- /.products-list -->

                            <div class="pagination-holder">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 text-left">
                                        <ul class="pagination">
                                            <?php echo yii\widgets\LinkPager::widget([
                                                'pagination' => $pager,
                                                'prevPageLabel' => '&#8249;',
                                                'nextPageLabel' => '&#8250;',
                                            ]) ?>
                                        </ul><!-- /.pagination -->
                                    </div>
                                </div><!-- /.row -->
                            </div><!-- /.pagination-holder -->

                        </div><!-- /.products-grid #list-view -->

                    </div><!-- /.tab-content -->
                </div><!-- /.grid-list-products -->

            </section><!-- /#gaming -->
        </div><!-- /.col -->
        <!-- ========================================= CONTENT : END ========================================= -->
    </div><!-- /.container -->
</section><!-- /#category-grid -->
<?php
if (Yii::$app->session->hasFlash('info')): ?>
    <script>
        alert('<?php echo Yii::$app->session->getFlash('info');?>');
    </script>
<?php endif; ?>
<!-- ============================================================= FOOTER ============================================================= -->
