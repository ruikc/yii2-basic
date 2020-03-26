<?php

/* @var $this yii\web\View */
/* @var $model common\models\App */

$this->title = '添加应用更新';
$this->params['breadcrumbs'][] = ['label' => '应用更新', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
