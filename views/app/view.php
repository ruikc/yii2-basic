<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\App */

$this->title = '应用更新包：' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '应用更新', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-view box box-primary">
    <div class="box-header with-border">
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
                'version',
                ['attribute' => 'file_id', 'label' => '下载链接(过期后刷新页面)', 'value' => function ($model) {
                    return $model->file_id ? \common\models\File::getUrl($model->file_id) : '';
                }],
                'created_at:datetime',
            ],
        ]) ?>
    </div>
</div>
