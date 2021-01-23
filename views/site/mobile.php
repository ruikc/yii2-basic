<?php

use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\LoginForm */

$this->title = '老干部App下载';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>
<style>
    .btn-download { width: 80%; padding: 10px; border-radius: 10px;font-size: 22px; margin-bottom: 30px; color: red;}

    .btn-download .fa { font-size: 30px; margin-right: 10px;}

    .tip {
        width: 100vw; height: 100vh; background: #000; opacity: 0.8; position: absolute; left: 0; top: 0;
        display: none;
        z-index: 1000;
    }

    .tip img { max-width: 100%;}

</style>
<div class="login-box">
    <div class="login-logo" style="color: white; margin-bottom: 80px;margin-top: 60px;">
        <a style="color: white;" href="#"><b>老干部APP下载</b></a>
    </div>
    <!-- /.login-logo -->
    <div style="text-align: center;">
        <?php
        $ua = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($ua, 'MicroMessenger') !== false) {
            echo Html::a('<i class="fa fa-android" aria-hidden="true"></i> 安卓手机', 'javascript:void(0);', ['class' => 'btn btn-default btn-download btn-topic-tip']);
        } else {
            echo Html::a('<i class="fa fa-android" aria-hidden="true"></i> 安卓手机', \common\models\File::getUrl($model->file_id), ['class' => 'btn btn-default btn-download', 'target' => '_blank']);
        }
        ?>
    </div>
    <div style="text-align: center;">
        <?= Html::a('<i class="fa fa-apple" aria-hidden="true"></i> 苹果手机', 'https://apps.apple.com/cn/app/%E5%86%9B%E4%BC%91%E6%A1%A3%E6%A1%88/id1519134875', ['class' => 'btn btn-default btn-download', 'target' => '_blank']) ?>
    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
<a href="javascript:void(0);" class="tip">
    <?= Html::img('@web/images/tip.png') ?>
</a>
<?php $this->beginBlock('js'); ?>
<script>
    $('body').on('click', '.btn-topic-tip', function () {
        $('.tip').css('display', 'block');
    }).on('click', '.tip', function () {
        $('.tip').css('display', 'none');
    });
</script>
<?php $this->endBlock(); ?>
