<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\helpers\Url;
use <?= $generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
<?= $generator->enablePjax ? 'use yii\widgets\Pjax;' : '' ?>

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index box box-primary">
<?= $generator->enablePjax ? "    <?php Pjax::begin(); ?>\n" : ''
?>    <div class="box-header with-border">
        <?= "<?= " ?>Html::button(<?= '\'<i class="fa fa-plus-square"></i>添加 '. Inflector::camel2words(StringHelper::basename($generator->modelClass)).'\'' ?>, ['class' => 'btn btn-success modal_show','data-url'=>Url::to(['create'])]) ?>
    </div>
<?php if(!empty($generator->searchModelClass)): ?>
<?= "    <?= "?> $this->render('_search', ['model' => $searchModel]); ?>
<?php endif; ?>
    <div class="box-body">
<?php
if ($generator->indexWidgetType === 'grid'):
    echo "        <?= " ?>GridView::widget([
            'dataProvider' => $dataProvider,
            <?= "'columns' => [\n"; ?>
<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            echo "                '" . $name . "',\n";
        } else {
            echo "                // '" . $name . "',\n";
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        if (++$count < 6) {
            echo "                '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        } else {
            echo "                // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        }
    }
}
?>
                ['attribute' => 'status', 'value' => function ($model) {
                    return $model::getStatus($model->status);
                }],
                ['class' => 'app\components\ActionColumn', 'header'=>'操作'],
            ],
        ]); ?>
<?php else: ?>
        <?= "<?= " ?>ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'item'],
            'itemView' => function ($model, $key, $index, $widget) {
                return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
            },
        ]) ?>
<?php endif; ?>
    </div>
<?= $generator->enablePjax ? "    <?php Pjax::end(); ?>\n" : '' ?>
</div>
