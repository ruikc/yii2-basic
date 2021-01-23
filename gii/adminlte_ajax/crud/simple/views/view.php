<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = $model-><?= $generator->getNameAttribute() ?>;
?>
<div class="modal-dialog <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?= "<?= " ?>$this->title<?= "?>" ?> </h4>
        </div>
        <div class="modal-body">
            <?= "<?= " ?>DetailView::widget([
                'model' => $model,
                'attributes' => [
            <?php
            if (($tableSchema = $generator->getTableSchema()) === false) {
                foreach ($generator->getColumnNames() as $name) {
                    echo "                '" . $name . "',\n";
                }
            } else {
                foreach ($generator->getTableSchema()->columns as $column) {
                    $format = stripos($column->name, 'created_at') !== false || stripos($column->name, 'updated_at') !== false ? 'datetime' : $generator->generateColumnFormat($column);
                    echo "                '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                }
            }
            ?>
                    ['attribute' => 'status', 'value' => function ($model) {
                        return $model::getStatus($model->status);
                    }],
                ],
            ]) ?>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
