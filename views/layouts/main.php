<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */


if (Yii::$app->controller->action->id === 'login' || Yii::$app->user->isGuest) {
    /**
     * Do not use this code in your template. Remove it.
     * Instead, use the code  $this->layout = '//main-login'; in your controller.
     */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {
    app\assets\AppAsset::register($this);
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-red sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php'
        ) ?>

        <?= $this->render(
            'left.php'
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content]
        ) ?>

    </div>

    <?php $this->endBody() ?>
    <div class="modal fade" id="remoteModal" tabindex="-1" data-backdrop="static" role="dialog"
         aria-labelledby="remoteModalLabel"></div>
    <?= $this->blocks['js'] ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
