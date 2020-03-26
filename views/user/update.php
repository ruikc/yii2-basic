<?php

/* @var $this yii\web\View */
/* @var $model app\models\Auth */

$this->title = '编辑员工: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => '员工管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="auth-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
