<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\QiniuAsset;

QiniuAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\App */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="app-form box box-primary">
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => '<div class="col-md-2" style="text-align: right;">{label}</div><div class="col-md-5" style="padding-left: 15px;padding-right: 15px;">{input}</div><div class="col-md-4">{error}</div><div class="clearfix"></div>',
        ],
        'options' => ['class' => 'form-layout']
    ]); ?>
    <div class="box-body">

        <?= $form->field($model, 'version')->textInput(['maxlength' => true]) ?>

        <div class="form-group" id="android-container">
            <div class="col-md-2" style="text-align: right;">
                <label class="control-label" for="app-file_id">上传安装包</label>
            </div>
            <div class="col-md-5" style="padding-left: 15px;padding-right: 15px;">
                <div class="file-upload-btn">
                    <button class="select-button">上传安装包</button>
                    <input type="file" class="file-input" id="upImage"
                           accept=".apk" data-list="file_list"
                           data-id="file_id" data-url="<?= \yii\helpers\Url::to(['/qiniu/token','type'=>'android']) ?>">
                </div>
                <div class="file-list" id="file_list"></div>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'file_id', ['template' => '{input} {error}'])->hiddenInput(['maxlength' => true, 'id' => 'file_id'])->label(false) ?>
            </div>
            <div class="clearfix"></div>
        </div>

        <?= $form->field($model, 'intro')->textarea(['rows' => 3]) ?>
        <?= $form->field($model, 'is_force')->dropDownList([0 => '不强制', 1 => '强制更新']) ?>

    </div>
    <div class="box-footer col-md-offset-2">
        <?= Html::submitButton('<i class="fa fa-window-maximize"></i>保存数据', ['class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="fa fa-reply"></i>返回', 'javascript:window.history.go(-1);', ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php $this->beginBlock('js') ?>
<script>
    var videos = new qiniuUpload();
    videos.uploader('uploadAndroid', {
        container: 'android-container',
        type: 'android',
        file_id: 'file_id',
        multi_selection: false,
        preview: false
    });
</script>
<?php $this->endBlock() ?>
<?php $this->beginBlock('js') ?>
    <script>
        var image = new QiNiu('upImage');
        image.init();
    </script>
<?php $this->endBlock() ?>