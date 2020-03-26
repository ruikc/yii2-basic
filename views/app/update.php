<?php

/* @var $this yii\web\View */
/* @var $model common\models\App */

$this->title = '编辑 App: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Apps', 'url' => ['index']];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="app-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
