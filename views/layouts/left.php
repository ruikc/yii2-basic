<?php

//$leftMenu = \backend\models\Menu::leftMenu();
$leftMenu = [
    ['label' => '用户管理', 'url' => ['/user'], 'active' => in_array(Yii::$app->controller->id, ['user','friend'])],
];
array_unshift($leftMenu, []);

if (YII_DEBUG) {
    $leftMenu[] = ['label' => '开发工具', 'icon' => '', 'items' => [
        ['label' => '菜单项', 'icon' => 'file-code-o', 'url' => ['/menu']],
        ['label' => '脚手架', 'icon' => 'file-code-o', 'url' => ['/gii']],
        ['label' => '调试器', 'icon' => 'dashboard', 'url' => ['/debug']],
    ]];
}
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => $leftMenu,
            ]
        ) ?>

    </section>

</aside>
