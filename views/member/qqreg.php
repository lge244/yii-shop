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
                    <h2 class="bordered">完善信息</h2>
                    <p>请根据要求，填写相关信息</p>

                    <?php $form = ActiveForm::begin([
                        'fieldConfig' => [
                            'template' => '{label}{input}{error}'
                        ]
                    ]); ?>

                    <?php echo $form->field($model, 'username')->textInput(['class' => 'le-input', 'placeholder' => '请输入用户名']); ?>
                    <?php echo $form->field($model, 'useremail')->textInput(['class' => 'le-input', 'placeholder' => '请输入邮箱']); ?>
                    <?php echo $form->field($model, 'userpass')->passwordInput(['class' => 'le-input', 'placeholder' => '请输入密码']) ?>
                    <?php echo $form->field($model, 'repass')->passwordInput(['class' => 'le-input', 'placeholder' => '重复密码']) ?>


                    <div class="buttons-holder">
                        <?php echo Html::submitButton('提交', ['class' => 'le-button huge']) ?>
                    </div><!-- /.buttons-holder -->
                    <?php ActiveForm::end(); ?>
                </section><!-- /.sign-in -->
            </div><!-- /.col -->

        </div><!-- /.row -->
    </div><!-- /.container -->
</main><!-- /.authentication -->
<!-- ========================================= MAIN : END ========================================= -->        <!-- ============================================================= FOOTER ============================================================= -->
