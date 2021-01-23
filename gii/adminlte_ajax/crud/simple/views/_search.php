<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->searchModelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-search box-header">

    <?= "<?php " ?>$form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
<?php if ($generator->enablePjax): ?>
        'options' => [
            'class' => 'form-inline',
            'data-pjax' => 1
        ],
<?php else :?>
        'options' => [
            'class' => 'form-inline'
        ],
<?php endif; ?>
    ]); ?>

<?php
$count = 0;
foreach ($generator->getColumnNames() as $attribute) {
    if (++$count < 6) {
        if( $attribute !='status' ){
            echo "    <?= " . $generator->generateActiveSearchField($attribute) . "->textInput(['placeholder' => '按关键字搜索'])->label(false) ?>\n\n";
        }else{
            echo "    <?= " . $generator->generateActiveSearchField($attribute) . "->dropDownList(['' => '全部状态', 10 => '启用', 20 => '禁用'])->label(false) ?>\n\n";
        }
    } else {
        if( $attribute != 'status' ){
            echo "    <?php // echo " . $generator->generateActiveSearchField($attribute) . "->textInput(['placeholder' => '按关键字搜索'])->label(false) ?>\n\n";
        }else{
            echo "    <?php // echo " . $generator->generateActiveSearchField($attribute) . "->dropDownList(['' => '全部状态', 10 => '启用', 20 => '禁用'])->label(false) ?>\n\n";
        }
    }
}
?>
    <div class="form-group">
        <?= "<?= " ?>Html::submitButton('<i class="fa fa-search"></i>搜索', ['class' => 'btn btn-default']) ?>
        <?= "<?= " ?>Html::button(<?= '\'<i class="fa fa-plus-square"></i>添加 '. Inflector::camel2words(StringHelper::basename($generator->modelClass)).'\'' ?>, ['class' => 'btn btn-success modal_show','data-url'=>Url::to(['create'])]) ?>
        <div class="help-block"></div>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
