<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;
?>
<div class="content-wrapper">
    <section class="content-header">
        <?php if (isset($this->blocks['content-header'])) { ?>
            <h1><?= $this->blocks['content-header'] ?></h1>
        <?php } else { ?>
            <h1>
                <?php
                if ($this->title !== null) {
                    echo \yii\helpers\Html::encode($this->title);
                } else {
                    echo \yii\helpers\Inflector::camel2words(
                        \yii\helpers\Inflector::id2camel($this->context->module->id)
                    );
                    echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                } ?>
            </h1>
        <?php } ?>

        <?=
        Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
    </section>

    <section class="content">
        <?= Alert::widget() ?>
        <?= $content ?>
    </section>
</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 2.0
    </div>
    Copyright &copy; 2014-<?=Yii::$app->formatter->asDate(time(),'Y')?> <a href="mailto:lhyfe1987@163.com">无敌远远</a> All rights reserved.  网站备案号：<a href="http://www.beian.miit.gov.cn" target="_blank">鲁ICP备12015075号-5</a>
    <span style="display: none;">
        <script type="text/javascript" src="https://v1.cnzz.com/z_stat.php?id=1279029782&web_id=1279029782"></script>
    </span>
</footer>
