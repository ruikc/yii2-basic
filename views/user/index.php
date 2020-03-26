<?php

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '员工管理';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
?>
<div class="auth-index box box-primary">
    <?= $this->render('_search', ['model' => $searchModel]); ?>
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'username',
                ['attribute' => 'dept_id','label'=>'所在部门', 'value' => function ($model) {
                    return $model->dept ? $model->dept->title : '无部门';
                }],
                'real_name',
                'sex',
                'mobile',
                ['attribute' => 'is_admin', 'value' => function ($model) {
                    if( $model->id == 1 ){
                        return '超级管理员';
                    }
                    return $model->is_admin == 2 ? '管理员' : '普通员工';
                }],
                ['attribute' => 'status', 'value' => function ($model) {
                    return $model::getStatus($model->status);
                }],
                'updated_at:datetime',
                [
                    'class' => 'app\components\ActionColumn',
                    'header' => '操作',
                    'visibleButtons' => [
                        'update' => function ($model) {
                            return $model->id != 1;
                        },
                        'delete' => function ($model) {
                            return $model->id != 1;
                        },
                    ]
                ],
            ],
        ]); ?>
    </div>
</div>
