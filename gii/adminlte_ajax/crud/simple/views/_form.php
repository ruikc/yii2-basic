<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form ">
    <?= "<?php " ?>$form = ActiveForm::begin([
        'id' => 'ajaxForm',
        'options' => ['class' => 'form-layout', 'onSubmit' => 'return false;'],
    ]); ?>
    <div class="modal-body">
        <?php foreach ($generator->getColumnNames() as $attribute) {
            if (in_array($attribute, $safeAttributes)) {
                if ($attribute != 'status') {
                    echo "<?= " . $generator->generateActiveField($attribute) . "->label('<i class=\"red\">* </i>'.\$model->getAttributeLabel('".$attribute."')) ?>\n\n";
                } else {
                    echo "<?= \$form->field(\$model, 'status')->dropDownList([10=>'启用',20=>'禁用'])->label('<i class=\"red\">* </i> 状态') ?>\n\n";
                }
            }
        } ?>
    </div>
    <div class="modal-footer">
        <?= "<?= " ?>Html::button('<i class="fa fa-times" aria-hidden="true"></i> 关闭窗口', ['class' => 'btn btn-default', 'onClick' => '$(".close").click()']) ?>
        <?= "<?= " ?>Html::submitButton('<i class="fa fa-window-maximize"></i> 保存数据', ['class' => 'btn btn-success']) ?>
    </div>
    <?= "<?php " ?>ActiveForm::end(); ?>
</div>
