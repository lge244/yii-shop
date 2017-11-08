<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
<!-- ============================================================= HEADER : END ============================================================= -->        <!-- ========================================= MAIN ========================================= -->
<main id="authentication" class="inner-bottom-md">
    <div class="container">
        <div class="row">

            <div class="col-md-6">
                <section class="section sign-in inner-right-xs">
                    <h2 class="bordered">登陆</h2>
                    <p>Hello, Welcome to your account</p>

                    <div class="social-auth-buttons">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="<?php echo yii\helpers\Url::to(['member/qqlogin']) ?>">
                                    <button class="btn-block btn-lg btn btn-facebook">QQ登陆
                                    </button>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button class="btn-block btn-lg btn btn-twitter"><i class="fa fa-twitter"></i> Sign In
                                    with Twitter
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php $form = ActiveForm::begin([
                        'fieldConfig' => [
                            'template' => '{label}{input}{error}'
                        ]
                    ]); ?>

                    <?php echo $form->field($model, 'loginName')->textInput(['class' => 'le-input', 'placeholder' => '请输入用户名或邮箱']); ?>
                    <?php echo $form->field($model, 'userpass')->passwordInput(['class' => 'le-input', 'placeholder' => '请输入密码']) ?>

                    <?php echo $form->field($model, 'rememberMe')->checkbox([
                        'class' => 'le-checbox auto-width inline',
                        'template' => '<span class="pull-left"><label class="content-color">{input}<span
                                            class="bold">记住我！</span></label></span>'

                    ]) ?>
                    <div class="field-row clearfix">
                        <span class="pull-right">
                        		<a href="#" class="content-color bold">忘记密码？</a>
                        	</span>
                    </div>

                    <div class="buttons-holder">
                        <?php echo Html::submitButton('登陆', ['class' => 'le-button huge']) ?>
                    </div><!-- /.buttons-holder -->
                    <?php ActiveForm::end(); ?>
                </section><!-- /.sign-in -->
            </div><!-- /.col -->

            <div class="col-md-6">
                <section class="section register inner-left-xs">
                    <h2 class="bordered">Create New Account</h2>
                    <p>Create your own Media Center account</p>

                    <form role="form" class="register-form cf-style-1">

                        <div class="field-row">
                            <label>Email</label>
                            <input type="text" class="le-input">
                        </div><!-- /.field-row -->

                        <div class="buttons-holder">
                            <button type="submit" class="le-button huge">Sign Up</button>
                        </div><!-- /.buttons-holder -->
                    </form>

                    <h2 class="semi-bold">Sign up today and you'll be able to :</h2>

                    <ul class="list-unstyled list-benefits">
                        <li><i class="fa fa-check primary-color"></i> Speed your way through the checkout</li>
                        <li><i class="fa fa-check primary-color"></i> Track your orders easily</li>
                        <li><i class="fa fa-check primary-color"></i> Keep a record of all your purchases</li>
                    </ul>

                </section><!-- /.register -->

            </div><!-- /.col -->

        </div><!-- /.row -->
    </div><!-- /.container -->
</main><!-- /.authentication -->
<!-- ========================================= MAIN : END ========================================= -->        <!-- ============================================================= FOOTER ============================================================= -->
