<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-search box-header">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'class' => 'form-inline'
        ],
    ]); ?>


    <?= $form->field($model, 'username')->textInput(['placeholder' => '按关键字搜索'])->label(false) ?>


    <?= $form->field($model, 'status')->dropDownList(['' => '全部状态', 10 => '启用', 20 => '禁用'])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-search"></i>搜索', ['class' => 'btn btn-default']) ?>
        <?= Html::a('<i class="fa fa-plus-square"></i>添加员工', ['create'], ['class' => 'btn btn-success']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
