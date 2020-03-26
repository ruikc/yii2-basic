<?php

/* @var $this yii\web\View */
/* @var $model app\models\Auth */

$this->title = '添加员工';
$this->params['breadcrumbs'][] = ['label' => '员工管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
