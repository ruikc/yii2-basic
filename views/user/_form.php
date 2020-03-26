<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\TreeAsset;

TreeAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\Auth */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-form box box-primary">
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => '<div class="col-md-2" style="text-align: right;">{label}</div><div class="col-md-5" style="padding-left: 15px;padding-right: 15px;">{input}</div><div class="col-md-4">{error}</div><div class="clearfix"></div>',
        ],
        'options' => ['class' => 'form-layout']
    ]); ?>
    <div class="box-body">
        <? ?>
        <div class="form-group required" style="position: relative;">
            <div class="col-md-2" style="text-align: right;">
                <label class="control-label" for="auth-password">
                    <i class="red">*</i> 选择部门：</label>
            </div>
            <div class="col-md-5" style="padding-left: 15px;padding-right: 15px;position: relative;">
                <input type="text" id="selectCatalog" class="form-control" autocomplete="off"
                       value="<?= $model->dept_id ? $model->dept->title : '' ?>" placeholder="请选择部门" readonly
                       onclick="$('#tree').show()"/>
                <div id="tree"
                     style="display: none; position:absolute; z-index:1010; background-color:white;left: 15px; top:35px; width: 250px"></div>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'dept_id', ['template' => '{input} {error}', 'options' => ['class' => '']])->hiddenInput(['id' => 'dept_id'])->label(false) ?>
                <div class="help-block"></div>
            </div>
            <div class="clearfix"></div>
        </div>

        <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'autocomplete' => 'off'])->label('<i class="red">*</i> 用户名：') ?>

        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'autocomplete' => 'new-password'])->label('<i class="red">*</i> 登录密码：') ?>

        <?= $form->field($model, 'real_name')->textInput(['maxlength' => true])->label('<i class="red">*</i> 真实姓名：') ?>

        <?= $form->field($model, 'sex')->dropDownList(['未知' => '未知', '男' => '男', '女' => '女'])->label('性别：') ?>

        <?= $form->field($model, 'mobile')->textInput()->label('手机号码：') ?>

        <?= $form->field($model, 'is_admin')->textInput()->dropDownList([1 => '普通员工', 2 => '管理员'])->label('<i class="red">*</i> 身份：') ?>

        <?= $form->field($model, 'status')->textInput()->radioList([\app\models\Base::STATUS_ACTIVE => '启用', \app\models\Base::STATUS_UNACTIVE => '禁用'])->label('<i class="red">*</i> ' . $model->getAttributeLabel('status') . '：') ?>

    </div>
    <div class="box-footer col-md-offset-2">
        <?= Html::submitButton('<i class=\"fa fa-window-maximize\"></i>保存数据', ['class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="fa fa-reply"></i>返回', 'javascript:window.history.go(-1);', ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php $this->beginBlock('js') ?>
<script>
    $(function () {
        var did = $('#dept_id').val();
        $.post('<?=\yii\helpers\Url::to(['/ajax/dept'])?>',
            {time: new Date().getTime(), 'did': did},
            function (res) {
                var options = {
                    levels: 1,
                    data: res.data,
                    onNodeSelected: function (event, data) {
                        $("#selectCatalog").val(data.text);
                        $("#dept_id").val(data.id);
                        $("#tree").hide();//选中树节点后隐藏树
                    },
                    onNodeUnselected: function (event, data) {
                        return false;
                    }
                };
                $('#tree').treeview(options);
            }
        )
    });
</script>
<?php $this->endBlock() ?>
