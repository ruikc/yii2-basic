<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Auth */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => '员工管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-view box box-primary">
    <div class="box-header with-border">
        <?= Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => '你确认要删除当前信息吗?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <div class="box-body">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'username',
                ['attribute' => 'dept_id','label'=>'所在部门', 'value' => function ($model) {
                    return $model->dept->title;
                }],
                ['attribute' => 'role_id', 'value' => function ($model) {
                    if( $model->id == 1 ){
                        return '超级管理员';
                    }
                    return $model->role_id ? '后台管理员' : '普通管理员';
                }],
                ['attribute' => 'status', 'value' => function ($model) {
                    return $model::getStatus($model->status);
                }],
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>
</div>
