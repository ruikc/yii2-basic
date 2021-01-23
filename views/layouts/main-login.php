<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

app\assets\AdminLteAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<style>
    .login-page {
        background-color:#cccccc ;
    }
</style>

<body class="login-page">

<?php $this->beginBody() ?>

<?= $content ?>

<?php $this->endBody() ?>
<?=$this->blocks['js']?>
</body>
</html>
<?php $this->endPage() ?>
