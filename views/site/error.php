<?php

use yii\helpers\Html;
use Yii;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

//$this->title = $name;
$this->title = '系统提示';
?>
<section class="content">

    <div class="error-page" style="text-align: center;">
        <h2 class="headline text-info" style="float: none;"><i class="fa fa-warning text-yellow"></i></h2>

        <div class="error-content" style="margin-left: 0px;">
            <h3 style="color: #dd4b39;">
                <?= nl2br(Html::encode($message)) ?>
            </h3>
            <p>
                <?= $name ?>
            </p>
            <p>
                <a href="<?= Yii::$app->request->referrer ?>" class="btn btn-danger btn-larage">返回操作</a>
            </p>

        </div>
    </div>

</section>
