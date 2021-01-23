<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Auth */
/* @var $form yii\widgets\ActiveForm */
$this->title = '修改密码';
?>

<div class="auth-form box box-primary">
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => '<div class="col-md-2" style="text-align: right;">{label}</div><div class="col-md-5" style="padding-left: 15px;padding-right: 15px;">{input}</div><div class="col-md-4">{error}</div><div class="clearfix"></div>',
        ],
        'options' => ['class' => 'form-layout']
    ]); ?>
    <div class="box-body">

        <?= $form->field($model, 'oldPassword')->passwordInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'confirmPassword')->passwordInput(['maxlength' => true]) ?>

    </div>
    <div class="box-footer col-md-offset-2">
        <?= Html::submitButton('修改密码', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
