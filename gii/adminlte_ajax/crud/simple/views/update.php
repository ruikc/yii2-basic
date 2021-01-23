<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= $generator->generateString('编辑 {modelClass}: ', ['modelClass' => Inflector::camel2words(StringHelper::basename($generator->modelClass))]) ?> . $model-><?= $generator->getNameAttribute() ?>;
?>
<div class="modal-dialog <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-create" role="update">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?= "<?= " ?>$this->title<?= "?>" ?></h4>
        </div>
        <?= "<?= " ?>$this->render('_form', [
            'model' => $model,
        ]) ?>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
