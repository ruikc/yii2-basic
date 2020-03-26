<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '应用更新';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
?>
<div class="app-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('<i class="fa fa-plus-square"></i>添加更新', ['create'], ['class' => 'btn btn-success']) ?>
    </div>
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'version',
                'intro',
                ['attribute' => 'is_force', 'value' => function ($model) {
                    return $model->is_force ? '是' : '否';
                }],
                'updated_at:datetime',
                ['class' => 'app\components\ActionColumn', 'header' => '操作','template'=>'{view} {delete}'],
            ],
        ]); ?>
    </div>
</div>
