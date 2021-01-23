<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
$user = Yii::$app->user->identity;
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">Chat</span><span class="logo-lg">Chat 系统管理后台</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <li class="user user-menu">
                    <a class="dropdown-toggle">
                        <span class="hidden-xs"><i class="fa fa-user-circle"></i><?=$user->username?></span>
                    </a>
                </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="hidden-xs"><i class="fa fa-download"></i>APP下载</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li style="padding: 20px;">
                            <img src="<?=\yii\helpers\Url::to(['/site/qrcode'])?>" alt="" style="max-width: 100%;">
                        </li>
                    </ul>
                </li>
                <li class="user user-menu">
                    <?= Html::a(
                        '<i class="fa fa-lock"></i>密码修改',
                        ['/site/reset']
                    ) ?>
                </li>
                <li class="user user-menu">
                    <?= Html::a(
                        '<i class="fa fa-sign-out"></i>退出登录',
                        ['/site/logout'],
                        ['data-method' => 'post']
                    ) ?>
                </li>
            </ul>
        </div>
    </nav>
</header>
