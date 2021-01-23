<?php

use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\LoginForm */

$this->title = '后台管理系统登录';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<div class="login-box">
    <div class="login-logo" style="color: white;">
        <a style="color: white;" href="#"><b>后台</b>管理系统登录</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">请先登录后台系统</p>

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

        <?= $form
            ->field($model, 'username', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' =>'登录账号']) ?>

        <?= $form
            ->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => '登录密码']) ?>

        <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
            'template' => '<div class="row"><div class="col-lg-6">{input}</div><div class="col-lg-3">{image}</div></div>',
            'options'=>['placeholder' => '图片验证码']
        ])->label(false) ?>

        <?= Html::submitButton('登 录', ['class' => 'btn btn-danger btn-block btn-flat', 'name' => 'login-button']) ?>

        <?php ActiveForm::end(); ?>
        <div style="width: 100%; padding: 10px 0 0 0; text-align: left;">
            网站备案号：<a href="http://www.beian.miit.gov.cn" style="color: #999;" target="_blank">鲁ICP备12015075号-5</a>
        </div>
        <div style="display: none">
            <script type="text/javascript" src="https://v1.cnzz.com/z_stat.php?id=1279029782&web_id=1279029782"></script>
        </div>
    </div>


    <!-- /.login-box-body -->
</div><!-- /.login-box -->
